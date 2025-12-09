<?php
require __DIR__ . '/../../vendor/autoload.php';
use PhpParser\Error;
use PhpParser\Node;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor;
use PhpParser\NodeVisitor\NodeConnectingVisitor;
use PhpParser\NodeVisitorAbstract;
use PhpParser\ParserFactory;

$code = file_get_contents(__DIR__ . '/temp.php');
$code = "<?php {$code}";

$parser_factory = new ParserFactory();
$parser = $parser_factory->createForNewestSupportedVersion();

try {
    $ast = $parser->parse($code);
} catch (Error $error) {
    echo "Parse error: {$error->getMessage()}\n";
    return;
}

$statements = array();
$traverser = new NodeTraverser((new NodeConnectingVisitor()));
$traverser->addVisitor(new class extends NodeVisitorAbstract
    {
        public function enterNode(Node $node)
        {
            global $statements;

            $type = $node->getType();
            if ($node instanceof Node\Stmt\Expression && $node->expr instanceof Node\Expr\FuncCall && $node->expr->name->toString() === 'var_dump') {
                $type = 'Expr_VarDump';
            }

            $statements[] = array(
                'start' => $node->getAttribute('startFilePos'),
                'end' => $node->getAttribute('endFilePos'),
                'type' => $type,
                'length' => $node->getAttribute('endFilePos') - $node->getAttribute('startFilePos') + 1,
            );

            // Stop traversing further into this node's children
            return NodeVisitor::DONT_TRAVERSE_CHILDREN;
        }
    });

$modified_stmts = $traverser->traverse($ast);

function get_longest_length($statements)
{
    // get only echo and vardump statements
    $statements = array_filter($statements, function($statement) {
            return in_array($statement['type'], array('Stmt_Echo', 'Expr_VarDump'));
    });

    usort($statements, function($a, $b) {
            return $b['length'] <=> $a['length'];
        });

    if (empty($statements)) {
        return 0;
    }

    return $statements[0]['length'] + 3;
}

$longest_length = get_longest_length($statements);

$output = $code;
$last_offset = 0;

foreach ($statements as &$stmt) {
    ob_start();
    $statement = substr($code, $stmt['start'], $stmt['length']);
    try {
        eval("use Carbon\Carbon; $statement");
        $captured_output = ob_get_clean();
        if (in_array($stmt['type'], array('Stmt_Echo', 'Expr_VarDump'))) {
            // add value as a comment next to the line in $code if single line
            // or after the line if multi line
            $trimmed_output = trim($captured_output);

            /* taqwim-disable-next-line taqwim/prefer-single-quotes */
            if (strpos($trimmed_output, "\n") !== false) {
                $comment = "\n/* {$trimmed_output} */";
            } else {
                $comment = " // {$trimmed_output}";
            }

            $padded_statment = str_pad($statement, $longest_length);

            // replace with padded statement
            $output = substr_replace($output, $padded_statment, $stmt['start'] + $last_offset, $stmt['length']);
            $last_offset += strlen($padded_statment) - $stmt['length'];

            // insert comment after the statement
            if ($trimmed_output) {
                $output = substr_replace($output, $comment, $stmt['end'] + 1 + $last_offset, 0);
                $last_offset += strlen($comment);
            }
        }
    } catch (\Throwable $error) {
        echo '\n\nError during eval: ' . $error->getMessage() . '\n';
    }
}

// remove <?php tag
$output = preg_replace('/^<\?php\s*/', '', $output);

print_r($output);
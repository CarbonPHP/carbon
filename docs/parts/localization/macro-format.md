{{locale::each($sampleLocales = \['en', 'fr', 'ja', 'hr'\])}} {{::endEach}} {{code::each(array\_keys($isoFormats = ($date = Carbon::parse('2017-01-05 17:04:05.084512'))->getIsoFormats()))}} {{::endEach}}

| Code                    | {{eval(echo $locale;)}} |
| ----------------------- | ----------------------- |
| `{{eval(echo $code;)}}` |
{{eval(echo preg\_match('/^L+$/', $code) ? '
`'.strtolower($code).'`' : '';)}} | {{eval(echo $date->locale($sampleLocales\[0\])->getIsoFormats()\[$code\];)}}
{{eval(echo $date->isoFormat($code);)}} {{eval(echo preg\_match('/^L+$/', $code) ? '
'.$date->isoFormat(strtolower($code)) : '';)}} | {{eval(echo $date->locale($sampleLocales\[1\])->getIsoFormats()\[$code\];)}}
{{eval(echo $date->isoFormat($code);)}} {{eval(echo preg\_match('/^L+$/', $code) ? '
'.$date->isoFormat(strtolower($code)) : '';)}} | {{eval(echo $date->locale($sampleLocales\[2\])->getIsoFormats()\[$code\];)}}
{{eval(echo $date->isoFormat($code);)}} {{eval(echo preg\_match('/^L+$/', $code) ? '
'.$date->isoFormat(strtolower($code)) : '';)}} | {{eval(echo $date->locale($sampleLocales\[3\])->getIsoFormats()\[$code\];)}}
{{eval(echo $date->isoFormat($code);)}} {{eval(echo preg\_match('/^L+$/', $code) ? '
'.$date->isoFormat(strtolower($code)) : '';)}} |

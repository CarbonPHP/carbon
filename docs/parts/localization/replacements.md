
{{code::each(array\_filter(array\_keys(Carbon::getIsoUnits()), function ($code) { return !preg\_match('/^hmm/i', $code); }))}} {{::endEach}}

| Code                  | Example                                 | Description                                     |
| --------------------- | --------------------------------------- | ----------------------------------------------- |
| {{eval(echo $code;)}} | {{eval(echo $date->isoFormat($code);)}} | {{eval(echo $date->describeIsoFormat($code);)}} |
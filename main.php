<?php
$file = __DIR__ . '/vendor/autoload.php';
if (!file_exists($file)) {
    throw new RuntimeException('Install dependencies to run test suite.');
}
$autoload = require_once $file;


$input = "list.txt";
$lines = file($input, FILE_IGNORE_NEW_LINES);

$err = array('','','','','');
$AVGtime = array('','','','','');
$AVGthou = array('','','','','');

$n=1;
foreach ($lines as $line) {
    $input = "bench/" . $line . '/stat_client0.html';
    if (!file_exists($input)) {
        echo "errore lettura file $input";
        die();
    }
    $parser = new simple_html_dom();
    $parser->load_file($input);
    if ($parser == NULL) {
        echo "errore apertura file $input";
        die();
    }

    $all = $parser->find('b');
    $i = 0;
    foreach ($all as $key => $e) {
        if ($e == NULL)
            break; //finished
        if ($e->plaintext != 'Total') {
            //echo "FAIL trovato: " . $e->plaintext . "\n";
            continue;
        }
        $i++;

        if($n==1)
            $comma = '';
        else
            $comma = ',';


        echo "\n\nFASE $i\n";
        //we got it
        //$all[$key+1]; 100%
        //$all[$key+2]; count
        //echo "count = ". $all[$key+2]->plaintext . "\n";
        //$all[$key+3]; errors
        $clean = preg_replace('/[^0-9.]+/', '', $all[$key + 3]->plaintext);
        echo "errors = " . $clean . "\n";
        $err[$i] = $err[$i] . "$comma " . $clean;
        //$all[$key+4]; avarange time
        $clean = preg_replace('/[^0-9.]+/', '', $all[$key + 4]->plaintext);
        echo "avg time = " . $clean . "\n";
        $AVGtime[$i] = $AVGtime[$i] . "$comma " . $clean;
        //$all[$key+5]; avarange throug
        $clean = preg_replace('/[^0-9.]+/', '', $all[$key + 6]->plaintext);
        echo "avg thoug = " . $clean . "\n";
        $AVGthou[$i] = $AVGthou[$i] . "$comma " . $clean;
    }
    $n++;
}

echo "\nTOTAL FILES PROCESSED: ".($n-1)."\n";
//cleaning files
shell_exec('./cleaner.sh');
//saving fresh results
//rump up
file_put_contents('results/rumpUP/errors.txt', $err[1]);
file_put_contents('results/rumpUP/time.txt', $AVGtime[1]);
file_put_contents('results/rumpUP/though.txt', $AVGthou[1]);
//runtime
file_put_contents('results/runTime/errors.txt', $err[2]);
file_put_contents('results/runTime/time.txt', $AVGtime[2]);
file_put_contents('results/runTime/though.txt', $AVGthou[2]);
//rump down
file_put_contents('results/rumpDown/errors.txt', $err[3]);
file_put_contents('results/rumpDown/time.txt', $AVGtime[3]);
file_put_contents('results/rumpDown/though.txt', $AVGthou[3]);
//overall
file_put_contents('results/overAll/errors.txt', $err[4]);
file_put_contents('results/overAll/time.txt', $AVGtime[4]);
file_put_contents('results/overAll/though.txt', $AVGthou[4]);

?>
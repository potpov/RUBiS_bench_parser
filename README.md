# RUBiS_bench_parser
a parser for the default rubis client outputs

# INSTALLATION

just run: composer install

# USE

after a cycle of emulations with the RUBiS client, move all the directories you got to the bench directory.
(there are some files here as example)
then run: ls bech > list.txt
and check the output inside the txt file to fix possible errors with date format.

to start the parser simply run: php main.php
you will get your result on the results folder.
foreach bench file, with cron order, the script filter the value of throughput, response time and number of errors for the 3 phases of the experiment and save them on a txt file compatible with matlab in order to quickly generate graph.

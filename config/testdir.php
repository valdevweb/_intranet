<?php

echo "hello";
$file = fopen('test.txt', "w") or die("Unable to open file!");

		fput($file, "hi");

	fclose($file);

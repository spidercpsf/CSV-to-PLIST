<?php
	putenv("LANG=jp_JP.UTF-8");
	$filename = "japanese_map.csv";
	
	
	ini_set('auto_detect_line_endings', true);
        $hasTitleRow = true;
        if (!file_exists($filename)) {
            return 0;
        }

        $pos = strrpos($filename, '.');
        if ($pos == FALSE) {
            $filename_out = $filename . '.plist';
        }
        else
            $filename_out = substr($filename, 0, $pos) . '.plist';

        $fp_in = fopen($filename, "r");
        $fp_out = fopen($filename_out, "w");

        // get title
        if ($hasTitleRow) {
            $data = fgetcsv($fp_in, 50000, ",");
            $num = count($data);
            $keys = array();
            for ($i = 0; $i < $num; $i++) {
                $keys[$i] = $data[$i];
            }
        }
        // generate plist
        fwrite($fp_out, "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n");
        fwrite($fp_out, "<!DOCTYPE plist PUBLIC \"-//Apple//DTD PLIST 1.0//EN\" \"http://www.apple.com/DTDs/PropertyList-1.0.dtd\">\n");
        fwrite($fp_out, "<plist version=\"1.0\">\n");
        fwrite($fp_out, "<array>\n");

        while ($data = fgetcsv($fp_in, 50000, ",")) {
            $num = count($data);
            fwrite($fp_out, "\t<dict>\n");
            for ($i = 0; $i < $num; $i++) {
                if ($hasTitleRow) {
                    fwrite($fp_out, "\t\t<key>$keys[$i]</key>\n");
                } else {
                    fwrite($fp_out, "\t\t<key>key$i</key>\n");
                }
                fwrite($fp_out, "\t\t<string>" . $data[$i] . "</string>\n");
            }
            fwrite($fp_out, "\t</dict>\n");
        }
        fclose($fp_in);

        fwrite($fp_out, "</array>\n");
        fwrite($fp_out, "</plist>\n");

        fclose($fp_out);

        return $filename_out;
?>

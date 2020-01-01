<?php

    define("CSVFORMAT","EST");
    //define("CSVFORMAT","ENG");
    function open_file($FileName,$flags){
        $filehandler = fopen($FileName,$flags);
        try{
            if(!$filehandler){
                throw new Exception("Cannot open file: " . $FileName . "\n");
            }
        }catch (Exception $e){
            echo "Error on open file\n" , $e->getMessage();
        }finally{

        }
        return $filehandler;
    }
    function is_csv_file($FileName){
        $result = False;
          try{
            $parts = explode(".",$FileName);
            $parts_cnt = count($parts);
            if($parts_cnt < 2){
                throw new Exception ("Wrong format: ". $FileName . "\n");
            }elseif(strcasecmp($parts[$parts_cnt-1],'csv')){
                throw new Exception("Wrong format: " . $FileName . "\n");
            }else{
                $result = True;
            }
            
          }catch (Exception $e){
            echo "Error in file format\n" , $e->getMessage();
          }
          return $result;
    }
    function make_sum_file_name($Filename){
        $filename_no_extension = pathinfo($Filename, PATHINFO_FILENAME);
        $sumfilename = $filename_no_extension . "_SUM" . ".csv";
        return $sumfilename;
    }

    function numdec_dot2comma($number){
        return str_replace(".",",",$number);
    }
    function numdec_comma2dot($number){
        return str_replace(",",".",$number);
    }

    function parse_file($FileName){
        $result = False;
        $colsum = array(0,0);
        $max_number_of_columns = 0;
        echo "Parsing file: " .   $FileName . "\n";
        if(!is_csv_file($FileName)){
            die("Not CSV\n");
        }

        $filehandler = open_file($FileName,'rb');
        $outputfilename = make_sum_file_name($FileName);
        $outputfilehandler = open_file($outputfilename,"wb");
        if(!$filehandler || !$outputfilehandler){
            return $result;
        }
        $totalsum = 0;
        $linecounter = 0;
        while ((!feof($filehandler)) && ($line = fgets($filehandler))){

            $line = trim($line);
            
            $cell = explode(';', $line);
            $numerofcolumns = count($cell);
            $emptycells = 0;
            //We assume that column names is always there and on the first row
            //So it gives the maximum number columns otherwise it would be column
            // without name and this is error situation we do not handle
            if($linecounter<1){
            //if($max_number_of_columns < $numerofcolumns){
                 $colsum=array_pad($colsum,$numerofcolumns,0);
                 $max_number_of_columns = $numerofcolumns;
                 $linecounter++;
                 fwrite($outputfilehandler,$line . "\n");
                 continue; //skipping the first row
             }else{
                 //how many colmns are missing
                 $emptycells = $max_number_of_columns - $numerofcolumns;
             }
            
            $linesum = 0;
            for($columnindex = 0; $columnindex < $numerofcolumns;$columnindex++){
                $cellnumber = numdec_comma2dot($cell[$columnindex]);
                if(is_numeric($cellnumber)){
                    $linesum += $cellnumber;
                    $colsum[$columnindex ] += $cellnumber;
                }
            }
            $totalsum += $linesum;
            fwrite($outputfilehandler,$line);
            //correcting by missing columns
            for($e = 0;$e < $emptycells+1; $e++){
                fwrite($outputfilehandler,';');
            }
            fwrite($outputfilehandler,(CSVFORMAT=="EST")?numdec_dot2comma($linesum) . "\n":$linesum . "\n");
            $linecounter++;
        }
        //foreach($colsum as $column){
        for($col=0;$col<$max_number_of_columns;$col++){
            if($col==0){
                fwrite($outputfilehandler, " " . ";");
            }else{
                fwrite($outputfilehandler,numdec_dot2comma($colsum[$col]) . ";");
            }
        }
        fwrite($outputfilehandler,numdec_dot2comma($totalsum) . "\n");
        fclose($filehandler);
        fclose($outputfilehandler);
        echo "<br>";
        return True;
    }

    function print_file($FileName){
        $filehandler = open_file($FileName,"rb");
        if(!$filehandler){
            die("Cannot print file\n");
        }
        while ((!feof($filehandler)) && ($line = fgets($filehandler))){
            $line = trim($line);
            echo "$line<br>";
        } 
    }
    function print_sum_file($FileName){
        $sumfilename = make_sum_file_name($FileName);
        print_file($sumfilename);
    }

?>

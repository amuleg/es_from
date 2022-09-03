<?php
namespace App\Classes;

class Logging
{
    public function __construct()
    {
        $this->filepath = '../lead_logs.json';
        $this->check_and_create_new_logfile();
    }

    public function save( $array )
    {
        $array = array_merge( ['timestamp' => time()], $array );
        if ( file_exists( $this->filepath ) ) {
            if ( filesize( $this->filepath ) <= 2 ) {
                $data = json_encode( [$array] );
                file_put_contents( $this->filepath, $data );
            } else {
                $json = json_encode( $array );
                $handle = fopen( $this->filepath, 'a+b' );
                ftruncate($handle, filesize( $this->filepath ) - 1);
                fwrite( $handle, ",{$json}]" );
                fclose( $handle );
            }
        }
    }

    protected function check_and_create_new_logfile()
    {
        if ( ! file_exists( $this->filepath ) ) {
            $handle = fopen( $this->filepath, 'w+' );
            $data = '[]';
            fwrite( $handle, $data );
            fclose( $handle );
        }
    }
}
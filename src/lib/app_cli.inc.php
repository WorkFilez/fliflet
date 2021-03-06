<?php

  require_once( DIR_LIB . '/i_application.inc.php' );
  require_once( DIR_LIB . '/scraper.inc.php' );

  class AppCli implements IApplication {
    private $mApp;
    public function __construct( $app ) {
      $this->mApp = $app;
    }
    public function doPreOperations() {
      $this->mApp->doPreOperations();
    }
    public function tpl( $idt, $data = null, $returnResult = false ) {
      switch( $idt ) {
        case 'main':
          global $argc, $argv;
          $this->mApp->tpl( 'cli_main', $returnResult );
          $this->main( $argc, $argv );
          break;
        default:
          $this->mApp->tpl( $idt, $data, $returnResult );
      }
    }
    public function doPostOperations() {
      $this->mApp->doPostOperations();
    }
    private function main( $argc, $argv ) {
      if( array_search( 'help', $argv ) !== FALSE ) {
        printf( "+-------------------------+------------------------------------------+\n" );
        printf( "| Command                 | Description                              |\n" );
        printf( "+-------------------------+------------------------------------------+\n" );
        printf( "| latex appendix          | Regenerate latex code for appendix       |\n" );
        printf( "| latex appendix-examples | Regenerate latex code for examples table |\n" );
        printf( "| list-suppliers          | List available suppliers (cached)        |\n" );
        printf( "| nuke-database           | Delete all data and recreate database    |\n" );
        printf( "| scrape                  | Start scraping target (oep.no)           |\n" );
        printf( "| regen-suppliers         | Refresh and output list of suppliers     |\n" );
        printf( "| regen-statistics        | Create statistics for current database   |\n" );
        printf( "+-------------------------+------------------------------------------+\n" );
      }
      else if( array_search( 'nuke-database', $argv ) !== FALSE ) {
        $db = Factory::getDatabase();
        $db->createDb();
        printf( "Database recreated.\n" );
      }
      else if( array_search( 'regen-statistics', $argv ) !== FALSE ) {
        $db = Factory::getDatabase();
        $db->regenStatistics();
        printf( "Statistics regenerated\n" );
      }
      else if( array_search( 'regen-suppliers', $argv ) !== FALSE ) {
        print_r( Factory::getSuppliers( TRUE ) );
      }
      else if( array_search( 'list-suppliers', $argv ) !== FALSE ) {
        print_r( Factory::getSuppliers( FALSE ) );
      }
      else if( array_search( 'regen-directions', $argv ) !== FALSE ) {
        $db = Factory::getDatabase();
        $db->setRecordsDirection();
        printf( "Directions regenerated\n" );
      }
      else if( array_search( 'scrape', $argv ) !== FALSE ) {
        $scraper = new Scraper();
        $scraper->run();
      }
    }

  }

?>

<?php
/*******************************************************************************
* Xmlhandle (fopen)
/*******************************************************************************
* Version 0.9.016
* Author:  Adriano Petrucci
* Copyright (c), IT-Master GmbH, All rights reserved
*******************************************************************************/
class xml_filehandle{
  public $_filename   = ""; 
  public $_filepfad   = "";
  public $_root       = "";
  public $_entryname  = "";
  public $_array      = NULL;
  public $_entryid    = 1;
  public $_biggestid  = 1;   
  
  function __construct($_filepfad, $_filename){
    $this->_filename = $_filename;
    $this->_filepfad = $_filepfad;
    $this->mkfile();
    $this->load_xml();
  }
  function mkfile(){
    if(!file_exists ($this->_filepfad)){
      mkdir($this->_filepfad);
    }
    if(!file_exists ($this->_filepfad.$this->_filename)){
      $neu = "<xml version=\"1.0\">\n\r</xml>";
      $open = fopen($this->_filepfad.$this->_filename,"w+");
      fwrite ($open, $neu);
      fclose($open);
    }
  }
  function clear_file(){
    $file = $this->_filepfad.$this->_filename;
    $fp = fopen($file,"w+");
    fputs($fp, "<xml version=\"1.0\">\n\r</xml>");
    fclose($fp);  
  }
  function delete_entry($id){
    unset($this->_array[$id]);
    $this->save_xml();
  }
  function update_entry($id, $values){
    $this->_array[$id] = $values;
    $this->save_xml();
  }
  function get_first_entry()
  {
    foreach($this->_array as $xmlElement)
    {
      return $xmlElement;
    }
    return null;
  }
  
  function save_xml()
  {
    $xml = new XMLWriter();
    $xml->openMemory();
    $xml->setIndent(true);
    $xml->startDocument("1.0", "UTF-8");
    $xml->startElement($this->_root);
    foreach($this->_array as $xmlElement)
    {
      $xml->startElement($this->_entryname);
      foreach($xmlElement as $key => $value)
      {
        $xml->writeAttribute($key, $value);
      }
      $xml->endElement();  
    }
    $xml->endElement();
    $xml->endDocument();
    
    file_put_contents($this->_filepfad.$this->_filename, $xml->outputMemory(true));
    
    $this->load_xml();
  }  
  function load_xml()
  {
    if(file_exists($this->_filepfad.$this->_filename))
    {
      $this->_entryid = 1;
      unset($this->$_array);
      
      $xml = new XMLReader();
      $xml->open($this->_filepfad.$this->_filename);
      if(!$xml->read()) return; // xml
      $this->_root = $xml->name;
      $this->_array = array();
      while($xml->read())
      {
        if($xml->nodeType == XMLReader::ELEMENT)
        {
          if($xml->hasAttributes)
          {
            if($this->_entryname == "") $this->_entryname = $xml->name;
            $values = array();
            $xmlAtt = $xml->expand()->attributes;
            for($j=0;$j<$xmlAtt->length;$j++)
              $values[$xmlAtt[$j]->name] = $xmlAtt[$j]->value;
            if(!isset($values['id']))
              $values['id'] = $this->_biggestid+1;
            if(is_numeric($values['id']) && $values['id'] >= $this->_biggestid) $this->_biggestid = $values['id'];
            
            $this->_array[$values['id']] = $values;
          }  
        }
      }
    } 
  }
  function user_exist($name){
    foreach($this->_array as $xmlentry)
      if($xmlentry['name'] == $name) return true;
    return false;
  }
  
  function get_anzahl(){
    return count($this->_array);
  }
  
  function insert_user($pfad, $name, $passwort, $rfid){
    $values = array();
    $values['id'] = $this->_biggestid + 1;
    $values['name'] = $name;
    $values['pfad'] = $pfad;
    $values['passwort'] = $passwort;
    $values['rfid'] = $rfid;
    $this->_array[$values['id']] = $values;
    $this->save_xml();
  }
  
  function update_user($_id, $pfad, $name, $passwort, $rfid){
    foreach($this->_array as $usr)
    {
      if($usr['pfad'] == $pfad)
      {
        $this->_array[$_id]['name'] = $name;
        $this->_array[$_id]['passwort'] = sha1($passwort);
        $this->_array[$_id]['rfid'] = $rfid;
        $this->save_xml();        
        break;
      }
    }
  }

  function add_user($_a){
    $_zeilenvorschub = "\r\n";
    if(!file_exists ("./Data/".$_a) || !is_dir("./Data/".$_a)){
      mkdir ("./Data/". $_a);
      mkdir ("./Data/". $_a. "/Dokumente");
      $file = "./Data/". $_a. "/Dokumente/.htaccess";
      $this->htaccess_txt($file);
      mkdir ("./Data/". $_a. "/Rapport");
      mkdir ("./Data/". $_a. "/Timetable");
      $file = "./Data/". $_a. "/Timetable/" . date('Y', time());
      $this->timetable_txt($file, 12, '0;;0;0');
      $file = "./Data/". $_a. "/Timetable/total.txt";
      $this->timetable_txt($file, 0, '0');
      mkdir ("./Data/". $_a. "/img");
      $this->htaccess_img($file);
      $file = "./Data/". $_a. "/absenz.txt";
      $this->absenz_txt($file);
      $file = "./Data/". $_a. "/userdaten.txt";
      $this->userdaten_txt($file);
    }
  }
  function timetable_txt($_file, $_i, $_txt){
    $_zeilenvorschub = "\r\n";
    $_zeilen = array();
    for($x=1; $x<=$_i; $x++){
      $_zeilen[] = $_txt;
    }
    $neu =implode($_zeilenvorschub,$_zeilen);
    $open = fopen($_file,"w+");
    fwrite ($open, $neu);
    fclose($open);
  }
  
  function write_file($text, $file){
    $fp = fopen($file,"w+");
    fputs($fp, $text);
    fclose($fp);  
  }
  function htaccess_img($_file){
    $_zeilenvorschub = "\r\n";
    $fp = fopen($_file,"w+");
    $text = 'Order deny,allow';
    fputs($fp, $text.$_zeilenvorschub);
    $text = 'Allow from all' ;
    fputs($fp, $text.$_zeilenvorschub);
    fputs($fp, '<Files ~ "\.(jpg)$">');
    fputs($fp, $_zeilenvorschub);
    fputs($fp, "  order deny,allow");
    fputs($fp, $_zeilenvorschub);
    fputs($fp, "  allow from all");
    fputs($fp, $_zeilenvorschub);
    fputs($fp, "</Files>");
    fputs($fp, $_zeilenvorschub);
    fputs($fp, "Options -Indexes");
    fputs($fp, $_zeilenvorschub);
    fclose($fp);
  }
  function htaccess_txt($_file){
    $_zeilenvorschub = "\r\n";
    $fp = fopen($_file,"w+");
    $text = 'Order deny,allow';
    fputs($fp, $text.$_zeilenvorschub);
    $text = 'Allow from all' ;
    fputs($fp, $text.$_zeilenvorschub);
    fputs($fp, '<Files "*">');
    fputs($fp, $_zeilenvorschub);
    fputs($fp, "  deny from all");
    fputs($fp, $_zeilenvorschub);
    fputs($fp, "</Files>");
    fputs($fp, $_zeilenvorschub);
    fclose($fp);
  }
  function absenz_txt($_file){
    $_zeilenvorschub = "\r\n";
    $fp = fopen($_file,"w+");
    $text = 'Ferien;F;100';
    fputs($fp, $text.$_zeilenvorschub);
    $text = 'Krankheit;K;100' ;
    fputs($fp, $text.$_zeilenvorschub);
    $text = 'Unfall;U;100';
    fputs($fp, $text.$_zeilenvorschub);
    $text = 'Militär;M;100';
    fputs($fp, $text.$_zeilenvorschub);
    $text = 'Intern;I;100';
    fputs($fp, $text.$_zeilenvorschub);
    $text = 'Weiterbildung;W;50';
    fputs($fp, $text.$_zeilenvorschub);
    $text = 'Extern;E;50';
    fputs($fp, $text.$_zeilenvorschub);
    fclose($fp);
  }
  function userdaten_txt($_file){
    $_zeilenvorschub = "\r\n";
    $fp = fopen($_file,"w+");
    $text = 'Vorname Nachname' ; 
    fputs($fp, $text.$_zeilenvorschub);
    //Start-Datum auf den jetztigen Monat setzten
    $text = mktime(0, 0, 0, date("n", time()), 1, date("Y", time())); ;
    fputs($fp, $text.$_zeilenvorschub);
    $text = '100' ;
    fputs($fp, $text.$_zeilenvorschub);
    $text = '42.5' ;
    fputs($fp, $text.$_zeilenvorschub);
    $text = '0' ; 
    fputs($fp, $text.$_zeilenvorschub);
    $text = '20' ; 
    fputs($fp, $text.$_zeilenvorschub);
    $text = '0;0' ;
    fputs($fp, $text.$_zeilenvorschub);
    $text = '0;1;1;1;1;1;0' ; 
    fputs($fp, $text.$_zeilenvorschub);
    $text = '1;0;0;0;1;1;1;1;1;1;1;0;1;0;0;1;1;1;0;1;1;0' ;
    fputs($fp, $text.$_zeilenvorschub);
    $text = '-1;-1;0' ;
    fputs($fp, $text.$_zeilenvorschub); // Sonntag
    fputs($fp, $text.$_zeilenvorschub); // Montag
    fputs($fp, $text.$_zeilenvorschub); // Dienstag
    fputs($fp, $text.$_zeilenvorschub); // Mittwoch
    fputs($fp, $text.$_zeilenvorschub); // Donnerstag
    fputs($fp, $text.$_zeilenvorschub); // Freitag
    fputs($fp, $text.$_zeilenvorschub); // Samstag
    $text = '0' ;
    fputs($fp, $text.$_zeilenvorschub);
    fclose($fp);
  }
  function delete_user($id){
    $pfad = $this->_array[$id]['pfad'];
    unset($this->_array[$id]);
    $this->save_xml();
    rename ("./Data/". $pfad, "./Data/_del_".date("Y.n.d")."_". $pfad);  
    $_txt = ""; 
    $_txt = $_txt.  "<br><br>User wurde etfernt und die Dateien verschoben nach ./Data/_del_".date("Y.n.d")."_". $pfad. "!";
    $_txt = $_txt.   "<br> Sichen Sie bitte das Verzeichniss und l&ouml;schen Sie es.";
    $_txt = $_txt.   "<br>Falls einmal ein gleicher Benutzer erstellt und dieser wieder gel&ouml;scht wird k&ouml;nnte es zu einer Fehlermeldung kommen.";
    return $_txt;
  } 
}
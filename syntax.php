<?php
/**
 * 
 * @author     Szymon Olewniczak
 */
 
// must be run within DokuWiki
if(!defined('DOKU_INC')) die();
 
/**
 * All DokuWiki plugins to extend the parser/rendering mechanism
 * need to inherit from this class
 */
class syntax_plugin_randompage2 extends DokuWiki_Syntax_Plugin {
 
public function getType(){ return 'formatting'; }
    public function getAllowedTypes() { return array('formatting', 'substition', 'disabled'); }   
    public function getSort(){ return 158; }
    public function connectTo($mode) { $this->Lexer->addEntryPattern('<randompage_link>(?=.*?</randompage_link>)',$mode,'plugin_randompage2'); }
    public function postConnect() { $this->Lexer->addExitPattern('</randompage_link>','plugin_randompage2'); }
 
 
    /**
     * Handle the match
     */
    public function handle($match, $state, $pos, Doku_Handler $handler){
        switch ($state) {
          case DOKU_LEXER_ENTER :
                return array($state, '');

          case DOKU_LEXER_UNMATCHED :  return array($state, $match);
          case DOKU_LEXER_EXIT :       return array($state, '');
        }
        return array();
    }
 
    /**
     * Create output
     */
    public function render($mode, Doku_Renderer $renderer, $data) {
		global $ID;
        // $data is what the function handle() return'ed.
        if($mode == 'xhtml'){
            /** @var Doku_Renderer_xhtml $renderer */
            list($state, $match) = $data;
            switch ($state) {
                case DOKU_LEXER_ENTER :      
                    $renderer->doc .= '<a href="'.wl($ID, array('do' => 'randompage'), true).'">'; 
                    break;
 
                case DOKU_LEXER_UNMATCHED :  
                    $renderer->doc .= $renderer->_xmlEntities($match); 
                    break;
                case DOKU_LEXER_EXIT :       
                    $renderer->doc .= '</a>'; 
                    break;
            }
            return true;
        }
        return false;
    }
}

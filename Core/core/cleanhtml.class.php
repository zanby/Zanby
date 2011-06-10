<?
################################################################################
# HtmlCleaner v0.5b                                                            #
# by Martin Sadera from www.e-d-a.info                                         #
# mailme to sadera@e-d-a.info                                                  #
# class is free for non-comercial use                                          #
################################################################################

class HtmlCleaner {
    var $lt             = "%#:";
    var $gt             = ":#%";
    var $html           = "";
    var $cleanedHtml    = "";
    var $allowedtags    = array("table","p","td","tr","th","tbody","thead");

    function HtmlCleaner($htmltoclean) {
        $this->html = $htmltoclean;
    }

    function GetCleanedHtml() {
        $this->cleanedHtml = $this->html;
        for ($i = 0; $i < count($this->allowedtags); $i++) {

            //arrays for fase 1 cleaning
            $pseudotags[]       = $this->lt . $this->allowedtags[$i] . $this->gt."<";
            $pseudotags[]       = $this->lt . $this->allowedtags[$i] . $this->gt."<";
            $pseudotags[]       = $this->lt . "/" . $this->allowedtags[$i] . $this->gt;
            $originaltags[]     = "<" . $this->allowedtags[$i] . " ";
            $originaltags[]     = "<" . $this->allowedtags[$i];
            $originaltags[]     = "</" . $this->allowedtags[$i] . ">";

            //arrays for fase 2 cleaning
            $pseudotags2[]      = $this->lt . $this->allowedtags[$i] . $this->gt;
            $pseudotags2[]      = $this->lt . "/" . $this->allowedtags[$i] . $this->gt;
            $originaltags2[]    = "<" . $this->allowedtags[$i] . ">";
            $originaltags2[]    = "</" . $this->allowedtags[$i] . ">";

            //remove empty tags like <p></p>
            $emptytags1[]       = "<" . $this->allowedtags[$i] . ">" . "</" . $this->allowedtags[$i] . ">";
            $emptytags2[]       = "";
        }

        //main cleaning utility
        $this->cleanedHtml = str_replace($originaltags, $pseudotags, $this->cleanedHtml);
        $this->cleanedHtml = strip_tags($this->cleanedHtml);
        $this->cleanedHtml = str_replace($pseudotags2, $originaltags2, $this->cleanedHtml);
        $this->cleanedHtml = preg_replace("/\r\n/","", $this->cleanedHtml);
        $this->cleanedHtml = preg_replace("/> *</","><", $this->cleanedHtml);
        return $this->cleanedHtml;
    }

    function allowedTags($tags) {
        $this->allowedtags = array();
        for ( $i = 0; $i < count($tags); $i++ ) {
            $tag = str_replace(array("<", ">", "/>"), array("", "", ""), $tags[$i]);
            $this->allowedtags[] = $tag;
        }
    }

    function saveToFile($fname) {
        $file_content = $this->GetCleanedHtml();
        $fp = fopen($fname, "w+");
        fwrite($fp, $file_content);
        fclose($fp);
    }

}
?>
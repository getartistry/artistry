<?php
if (!class_exists("wpdreamsFontComplete")) {
    /**
     * Class wpdreamsFontComplete
     *
     * A more advanced font selector UI element with font-shadow included.
     *
     * @package  WPDreams/OptionsFramework/Classes
     * @category Class
     * @author Ernest Marcinko <ernest.marcinko@wp-dreams.com>
     * @link http://wp-dreams.com, http://codecanyon.net/user/anago/portfolio
     * @copyright Copyright (c) 2014, Ernest Marcinko
     */
    class wpdreamsFontComplete extends wpdreamsType {

        public static $loadedFonts = array();

        function getType() {
            parent::getType();
            $this->processData();
            $applied_style = "font-family:" . ($this->font) . ";font-weight:" . $this->weight . ";line-height:" . $this->lineheight . ";color:" . $this->color;
            echo "<div class='wpdreamsFontComplete'>
        <fieldset>
        <legend>" . $this->label . "</legend>
      ";
            echo "<label wpddata='testText' for='wpdreamsfontcomplete_" . self::$_instancenumber . "' style=\"" . $applied_style . "\">Test Text :)</label>";
            new wpdreamsColorPickerDummy(self::$_instancenumber . "_fontColor", "", (isset($this->color) ? $this->color : "#000000"));
            echo "<select class='wpdreamsfontcompleteselect' id='wpdreamsfontcomplete_" . self::$_instancenumber . "' name='" . self::$_instancenumber . "_select'>";
            $options = '
        <option value="inherit" style="inherit">inherit</option>
        <option disabled>-------Classic Webfonts-------</option>
        <option value="\'Arial\', Helvetica, sans-serif" style="font-family:Arial, Helvetica, sans-serif">Arial, Helvetica, sans-serif</option>
        <option value="\'Arial Black\', Gadget, sans-serif" style="font-family:\'Arial Black\', Gadget, sans-serif">"Arial Black", Gadget, sans-serif</option>
        <option value="\'Comic Sans MS\', cursive" style="font-family:\'Comic Sans MS\', cursive">"Comic Sans MS", cursive</option>
        <option value="\'Courier New\', Courier, monospace" style="font-family:\'Courier New\', Courier, monospace">"Courier New", Courier, monospace</option>
        <option value="\'Georgia\', serif" style="font-family:Georgia, serif">Georgia, serif</option>
        <option value="\'Impact\', Charcoal, sans-serif" style="font-family:Impact, Charcoal, sans-serif">Impact, Charcoal, sans-serif</option>
        <option value="\'Lucida Console\', Monaco, monospace" style="font-family:\'Lucida Console\', Monaco, monospace">"Lucida Console", Monaco, monospace</option>
        <option value="\'Lucida Sans Unicode\', \'Lucida Grande\', sans-serif" style="font-family:\'Lucida Sans Unicode\', \'Lucida Grande\', sans-serif">"Lucida Sans Unicode", "Lucida Grande", sans-serif</option>
        <option value="\'Palatino Linotype\', \'Book Antiqua\', Palatino, serif" style="font-family:\'Palatino Linotype\', \'Book Antiqua\', Palatino, serif">"Palatino Linotype", "Book Antiqua", Palatino, serif</option>
        <option value="\'Tahoma\', Geneva, sans-serif" style="font-family:Tahoma, Geneva, sans-serif">Tahoma, Geneva, sans-serif</option>
        <option value="\'Times New Roman\', Times, serif" style="font-family:\'Times New Roman\', Times, serif">"Times New Roman", Times, serif</option>
        <option value="\'Trebuchet MS\', Helvetica, sans-serif" style="font-family:\'Trebuchet MS\', Helvetica, sans-serif">"Trebuchet MS", Helvetica, sans-serif</option>
        <option value="\'Verdana\', Geneva, sans-serif" style="font-family:Verdana, Geneva, sans-serif">Verdana, Geneva, sans-serif</option>
        <option value="\'Symbol\'" style="font-family:Symbol">Symbol</option>
        <option value="\'Webdings\'" style="font-family:Webdings">Webdings</option>
        <option value="\'Wingdings\', \'Zapf Dingbats\'" style="font-family:Wingdings, \'Zapf Dingbats\'">Wingdings, "Zapf Dingbats"</option>
        <option value="\'MS Sans Serif\', Geneva, sans-serif" style="font-family:\'MS Sans Serif\', Geneva, sans-serif">"MS Sans Serif", Geneva, sans-serif</option>
        <option value="\'MS Serif\', \'New York\', serif" style="font-family:\'MS Serif\', \'New York\', serif">"MS Serif", "New York", serif</option>
        <option disabled>-------Google Webfonts-------</option>
        <option  value="--g--Allan" style="font-family: Allan,Allan;"> Allan</option>
        <option  value="--g--Allerta" style="font-family: Allerta,Allerta;"> Allerta</option>
        <option  value="--g--Allerta Stencil" style="font-family: Allerta Stencil,Allerta Stencil;"> Allerta Stencil</option>
        <option  value="--g--Anonymous Pro" style="font-family: Anonymous Pro,Anonymous Pro;"> Anonymous Pro</option>
        <option  value="--g--Arimo" style="font-family: Arimo,Arimo;"> Arimo</option>
        <option  value="--g--Arvo" style="font-family: Arvo,Arvo;"> Arvo</option>
        <option  value="--g--Bentham" style="font-family: Bentham,Bentham;"> Bentham</option>
        <option  value="--g--Buda" style="font-family: Buda,Buda;"> Buda</option>
        <option  value="--g--Cabin" style="font-family: Cabin,Cabin;"> Cabin</option>
        <option  value="--g--Calligraffitti" style="font-family: Calligraffitti,Calligraffitti;"> Calligraffitti</option>
        <option  value="--g--Cantarell" style="font-family: Cantarell,Cantarell;"> Cantarell</option>
        <option  value="--g--Cardo" style="font-family: Cardo,Cardo;"> Cardo</option>
        <option  value="--g--Cherry Cream Soda" style="font-family: Cherry Cream Soda,Cherry Cream Soda;"> Cherry Cream Soda</option>
        <option  value="--g--Chewy" style="font-family: Chewy,Chewy;"> Chewy</option>
        <option  value="--g--Coda" style="font-family: Coda,Coda;"> Coda</option>
        <option  value="--g--Coming Soon" style="font-family: Coming Soon,Coming Soon;"> Coming Soon</option>
        <option  value="--g--Copse" style="font-family: Copse,Copse;"> Copse</option>
        <option  value="--g--Corben" style="font-family: Corben,Corben;"> Corben</option>
        <option  value="--g--Cousine" style="font-family: Cousine,Cousine;"> Cousine</option>
        <option  value="--g--Covered By Your Grace" style="font-family: Covered By Your Grace,Covered By Your Grace;"> Covered By Your Grace</option>
        <option  value="--g--Crafty Girls" style="font-family: Crafty Girls,Crafty Girls;"> Crafty Girls</option>
        <option  value="--g--Crimson Text" style="font-family: Crimson Text,Crimson Text;"> Crimson Text</option>
        <option  value="--g--Crushed" style="font-family: Crushed,Crushed;"> Crushed</option>
        <option  value="--g--Cuprum" style="font-family: Cuprum,Cuprum;"> Cuprum</option>
        <option  value="--g--Droid Sans" style="font-family: Droid Sans,Droid Sans;"> Droid Sans</option>
        <option  value="--g--Droid Sans Mono" style="font-family: Droid Sans Mono,Droid Sans Mono;"> Droid Sans Mono</option>
        <option  value="--g--Droid Serif" style="font-family: Droid Serif,Droid Serif;"> Droid Serif</option>
        <option  value="--g--Fontdiner Swanky" style="font-family: Fontdiner Swanky,Fontdiner Swanky;"> Fontdiner Swanky</option>
        <option  value="--g--GFS Didot" style="font-family: GFS Didot,GFS Didot;"> GFS Didot</option>
        <option  value="--g--GFS Neohellenic" style="font-family: GFS Neohellenic,GFS Neohellenic;"> GFS Neohellenic</option>
        <option  value="--g--Geo" style="font-family: Geo,Geo;"> Geo</option>
        <option  value="--g--Gruppo" style="font-family: Gruppo,Gruppo;"> Gruppo</option>
        <option  value="--g--Hanuman" style="font-family: Hanuman,Hanuman;"> Hanuman</option>
        <option  value="--g--Homemade Apple" style="font-family: Homemade Apple,Homemade Apple;"> Homemade Apple</option>
        <option  value="--g--IM Fell DW Pica" style="font-family: IM Fell DW Pica,IM Fell DW Pica;"> IM Fell DW Pica</option>
        <option  value="--g--IM Fell DW Pica SC" style="font-family: IM Fell DW Pica SC,IM Fell DW Pica SC;"> IM Fell DW Pica SC</option>
        <option  value="--g--IM Fell Double Pica" style="font-family: IM Fell Double Pica,IM Fell Double Pica;"> IM Fell Double Pica</option>
        <option  value="--g--IM Fell Double Pica SC" style="font-family: IM Fell Double Pica SC,IM Fell Double Pica SC;"> IM Fell Double Pica SC</option>
        <option  value="--g--IM Fell English" style="font-family: IM Fell English,IM Fell English;"> IM Fell English</option>
        <option  value="--g--IM Fell English SC" style="font-family: IM Fell English SC,IM Fell English SC;"> IM Fell English SC</option>
        <option  value="--g--IM Fell French Canon" style="font-family: IM Fell French Canon,IM Fell French Canon;"> IM Fell French Canon</option>
        <option  value="--g--IM Fell French Canon SC" style="font-family: IM Fell French Canon SC,IM Fell French Canon SC;"> IM Fell French Canon SC</option>
        <option  value="--g--IM Fell Great Primer" style="font-family: IM Fell Great Primer,IM Fell Great Primer;"> IM Fell Great Primer</option>
        <option  value="--g--IM Fell Great Primer SC" style="font-family: IM Fell Great Primer SC,IM Fell Great Primer SC;"> IM Fell Great Primer SC</option>
        <option  value="--g--Inconsolata" style="font-family: Inconsolata,Inconsolata;"> Inconsolata</option>
        <option  value="--g--Irish Growler" style="font-family: Irish Growler,Irish Growler;"> Irish Growler</option>
        <option  value="--g--Josefin Sans" style="font-family: Josefin Sans,Josefin Sans;"> Josefin Sans</option>
        <option  value="--g--Josefin Slab" style="font-family: Josefin Slab,Josefin Slab;"> Josefin Slab</option>
        <option  value="--g--Just Another Hand" style="font-family: Just Another Hand,Just Another Hand;"> Just Another Hand</option>
        <option  value="--g--Just Me Again Down Here" style="font-family: Just Me Again Down Here,Just Me Again Down Here;"> Just Me Again Down Here</option>
        <option  value="--g--Kenia" style="font-family: Kenia,Kenia;"> Kenia</option>
        <option  value="--g--Kranky" style="font-family: Kranky,Kranky;"> Kranky</option>
        <option  value="--g--Kristi" style="font-family: Kristi,Kristi;"> Kristi</option>
        <option  value="--g--Lato" style="font-family: Lato,Lato;"> Lato</option>
        <option  value="--g--Lekton" style="font-family: Lekton,Lekton;"> Lekton</option>
        <option  value="--g--Lobster" style="font-family: Lobster,Lobster;"> Lobster</option>
        <option  value="--g--Luckiest Guy" style="font-family: Luckiest Guy,Luckiest Guy;"> Luckiest Guy</option>
        <option  value="--g--Merriweather" style="font-family: Merriweather,Merriweather;"> Merriweather</option>
        <option  value="--g--Molengo" style="font-family: Molengo,Molengo;"> Molengo</option>
        <option  value="--g--Mountains of Christmas" style="font-family: Mountains of Christmas,Mountains of Christmas;"> Mountains of Christmas</option>
        <option  value="--g--Neucha" style="font-family: Neucha,Neucha;"> Neucha</option>
        <option  value="--g--Neuton" style="font-family: Neuton,Neuton;"> Neuton</option>
        <option  value="--g--Nobile" style="font-family: Nobile,Nobile;"> Nobile</option>
        <option  value="--g--OFL Sorts Mill Goudy TT" style="font-family: OFL Sorts Mill Goudy TT,OFL Sorts Mill Goudy TT;"> OFL Sorts Mill Goudy TT</option>
        <option  value="--g--Old Standard TT" style="font-family: Old Standard TT,Old Standard TT;"> Old Standard TT</option>
        <option  value="--g--Orbitron" style="font-family: Orbitron,Orbitron;"> Orbitron</option>
        <option  value="--g--Open Sans" style="font-family: Open Sans, Open Sans;"> Open Sans</option>
        <option  value="--g--PT Sans" style="font-family: PT Sans,PT Sans;"> PT Sans</option>
        <option  value="--g--PT Sans Caption" style="font-family: PT Sans Caption,PT Sans Caption;"> PT Sans Caption</option>
        <option  value="--g--PT Sans Narrow" style="font-family: PT Sans Narrow,PT Sans Narrow;"> PT Sans Narrow</option>
        <option  value="--g--Permanent Marker" style="font-family: Permanent Marker,Permanent Marker;"> Permanent Marker</option>
        <option  value="--g--Philosopher" style="font-family: Philosopher,Philosopher;"> Philosopher</option>
        <option  value="--g--Puritan" style="font-family: Puritan,Puritan;"> Puritan</option>
        <option  value="--g--Raleway" style="font-family: Raleway,Raleway;"> Raleway</option>
        <option  value="--g--Reenie Beanie" style="font-family: Reenie Beanie,Reenie Beanie;"> Reenie Beanie</option>
        <option  value="--g--Rock Salt" style="font-family: Rock Salt,Rock Salt;"> Rock Salt</option>
        <option  value="--g--Schoolbell" style="font-family: Schoolbell,Schoolbell;"> Schoolbell</option>
        <option  value="--g--Slackey" style="font-family: Slackey,Slackey;"> Slackey</option>
        <option  value="--g--Sniglet" style="font-family: Sniglet,Sniglet;"> Sniglet</option>
        <option  value="--g--Sunshiney" style="font-family: Sunshiney,Sunshiney;"> Sunshiney</option>
        <option  value="--g--Syncopate" style="font-family: Syncopate,Syncopate;"> Syncopate</option>
        <option  value="--g--Tangerine" style="font-family: Tangerine,Tangerine;"> Tangerine</option>
        <option  value="--g--Tinos" style="font-family: Tinos,Tinos;"> Tinos</option>
        <option  value="--g--Ubuntu" style="font-family: Ubuntu,Ubuntu;"> Ubuntu</option>
        <option  value="--g--UnifrakturCook" style="font-family: UnifrakturCook,UnifrakturCook;"> UnifrakturCook</option>
        <option  value="--g--UnifrakturMaguntia" style="font-family: UnifrakturMaguntia,UnifrakturMaguntia;"> UnifrakturMaguntia</option>
        <option  value="--g--Unkempt" style="font-family: Unkempt,Unkempt;"> Unkempt</option>
        <option  value="--g--Vibur" style="font-family: Vibur,Vibur;"> Vibur</option>
        <option  value="--g--Vollkorn" style="font-family: Vollkorn,Vollkorn;"> Vollkorn</option>
        <option  value="--g--Walter Turncoat" style="font-family: Walter Turncoat,Walter Turncoat;"> Walter Turncoat</option>
        <option  value="--g--Yanone Kaffeesatz" style="font-family: Yanone Kaffeesatz,Yanone Kaffeesatz;"> Yanone Kaffeesatz</option>
      ';
            $options = explode("<option", $options);
            unset($options[0]);
            foreach ($options as $option) {
                if (strpos(stripslashes($option), '"' . stripslashes($this->font) . '"') !== false) {
                    echo "<option selected='selected' " . $option;
                } else {
                    echo "<option " . $option;
                }
            }
            if ($this->weight == "")
                $this->weight = "normal";
            echo "</select>";
            echo "<input type='hidden' value='wpdreamsFontComplete' name='wpdfont-" . $this->name . "'>";
            echo "<br><input isparam=1 type='hidden' value=\"" . $this->data . "\" name='" . $this->name . "'>";
            echo "<input class='wpdreams-fontweight' name='" . self::$_instancenumber . "_font-weight' type='radio' value='normal' " . (($this->weight == 'normal') ? 'checked' : '') . ">Normal</input>";
            echo "<input class='wpdreams-fontweight' name='" . self::$_instancenumber . "_font-weight' type='radio' value='bold' " . (($this->weight == 'bold') ? 'checked' : '') . ">Bold</input>";
            echo "<br><span>Font size (ex.:10em, 10px or 110%): </span>";
            echo "<input type='text' class='wpdreams-fontsize threedigit' name='" . self::$_instancenumber . "_size' value='" . $this->size . "' />";
            echo "<span>Line height: </span><input type='text' class='wpdreams-lineheight threedigit' name='" . self::$_instancenumber . "_lineheight' value='" . $this->lineheight . "' />";
            echo "
                 <h4>Text shadow options</h4>
                 <label>Vertical offset</label><input wpddata='hlength' type='text' class='twodigit' name='_xx_hlength_xx_' value='" . $this->hlength . "' />px
                 <label>Horizontal offset</label><input wpddata='vlength' type='text' class='twodigit' name='_xx_vlength_xx_' value='" . $this->vlength . "' />px
                 <label>Blur radius</label><input wpddata='blurradius' type='text' class='twodigit' name='_xx_blurradius_xx_' value='" . $this->blurradius . "' />px

              ";
            new wpdreamsColorPickerDummy(self::$_instancenumber . "_tsColor", "Shadow color", (isset($this->tsColor) ? $this->tsColor : "#000000"));
            echo "
                <div class='triggerer'></div>
                <script>
                jQuery(document).ready(function($) {
                    setTimeout(function() {
                        $('#wpdreamsfontcomplete_" . self::$_instancenumber . "').change();
                    }, 2000);
                }(jQuery));
                </script>
              </fieldset>
              </div>";
        }

        function processData() {
            $this->data = str_replace('\\', "", stripcslashes($this->data));
            preg_match("/family:(.*?);/", $this->data, $_fonts);
            $this->font = $_fonts[1];
            preg_match("/weight:(.*?);/", $this->data, $_weight);
            $this->weight = $_weight[1];
            preg_match("/color:(.*?);/", $this->data, $_color);
            $this->color = $_color[1];
            preg_match("/size:(.*?);/", $this->data, $_size);
            $this->size = $_size[1];
            preg_match("/height:(.*?);/", $this->data, $_lineheight);
            $this->lineheight = $_lineheight[1];
            preg_match("/text-shadow:(.*?)px (.*?)px (.*?)px (.*?);/", $this->data, $matches);

            // Backwards compatibility
            if (is_array($matches) && isset($matches[1])) {
                $this->hlength = $matches[1];
                $this->vlength = $matches[2];
                $this->blurradius = $matches[3];
                $this->tsColor = $matches[4];
            } else {
                $this->hlength = '0';
                $this->vlength = '0';
                $this->blurradius = '0';
                $this->tsColor = 'rgba(255, 255, 255, 0)';
            }
        }

        final function getData() {
            return $this->data;
        }

        final function getScript() {
            if (strpos($this->font, "'"))
                return;
            if (strpos($this->font, '--g--') === false)
                return;
            $font = str_replace("--g--", "", trim($this->font));
            $font = str_replace(" ", "+", trim($this->font));
            if (isset(wpdreamsFont::$loadedFonts[$font]))
                return;
            wpdreamsFont::$loadedFonts[$font] = 1;
            ob_start();
            ?>
            <style>
                @import url(https://fonts.googleapis.com/css?family=

                <?php echo $font; ?>
                :300|
                <?php echo $font; ?>
                :400|
                <?php echo $font; ?>
                :700
                )
                ;
            </style>
            <?php
            $out = ob_get_contents();
            ob_end_clean();
            return $out;
        }

        final function getImport() {
            if (strpos($this->font, "'"))
                return;
            if (strpos($this->font, '--g--') === false)
                return;
            $font = str_replace("--g--", "", trim($this->font));
            $font = str_replace(" ", "+", $font);
            if (isset(wpdreamsFont::$loadedFonts[$font]))
                return;
            wpdreamsFont::$loadedFonts[$font] = 1;
            ob_start();
            ?>
            @import url(https://fonts.googleapis.com/css?family=<?php echo $font; ?>:300|<?php echo $font; ?>:400|<?php echo $font; ?>:700);
            <?php
            $out = ob_get_contents();
            ob_end_clean();
            return $out;
        }
    }
}
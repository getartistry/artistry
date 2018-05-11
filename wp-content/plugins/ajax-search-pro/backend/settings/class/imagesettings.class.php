<?php
if (!class_exists("wpdreamsImageSettings")) {
    /**
     * Class wpdreamsImageSettings
     *
     * DEPRECATED
     *
     * @deprecated
     * @package  WPDreams/OptionsFramework/Classes
     * @category Class
     * @author Ernest Marcinko <ernest.marcinko@wp-dreams.com>
     * @link http://wp-dreams.com, http://codecanyon.net/user/anago/portfolio
     * @copyright Copyright (c) 2014, Ernest Marcinko
     */
    class wpdreamsImageSettings extends wpdreamsType {
        function getType() {
            parent::getType();
            $this->processData();
            echo "
      <div class='wpdreamsImageSettings'>
        <fieldset>
          <legend>" . $this->label . "</legend>";
            new wpdreamsYesNo("show", "Show Images", $this->show);
            echo "<br>";
            new wpdreamsYesNo("cache", "Cache Images", $this->cache);
            echo "
         <br>
         <label>Use custom field as image</label><select class='smaller' name='usecustom'>
             <option value='-11' " . (($this->usecustom == -11) ? 'selected="selected"' : '') . ">Don't use</option>
             <option value='-1' " . (($this->usecustom == -1) ? 'selected="selected"' : '') . ">Highest Priority</option>
             <option value='0' " . (($this->usecustom == 0) ? 'selected="selected"' : '') . ">High Priority</option>
             <option value='1' " . (($this->usecustom == 1) ? 'selected="selected"' : '') . ">Medium Priority</option>
             <option value='2' " . (($this->usecustom == 2) ? 'selected="selected"' : '') . ">Low Priority</option>
         </select><br>
         <label>Custom field name</label><input param=0 type='text' value='" . $this->customname . "' name='customname' /><br>
         <label>Use post featured image</label><select class='smaller' name='featured'>
             <option value='-11' " . (($this->featured == -11) ? 'selected="selected"' : '') . ">Don't use</option>
             <option value='-1' " . (($this->featured == -1) ? 'selected="selected"' : '') . ">Highest Priority</option>
             <option value='0' " . (($this->featured == 0) ? 'selected="selected"' : '') . ">High Priority</option>
             <option value='1' " . (($this->featured == 1) ? 'selected="selected"' : '') . ">Medium Priority</option>
             <option value='2' " . (($this->featured == 2) ? 'selected="selected"' : '') . ">Low Priority</option>
         </select><br>
         <label>Search for images in post content</label><select class='smaller' name='content'>
             <option value='-11' " . (($this->content == -11) ? 'selected="selected"' : '') . ">Don't use</option>
             <option value='-1' " . (($this->content == -1) ? 'selected="selected"' : '') . ">Highest Priority</option>
             <option value='0' " . (($this->content == 0) ? 'selected="selected"' : '') . ">High Priority</option>
             <option value='1' " . (($this->content == 1) ? 'selected="selected"' : '') . ">Medium Priority</option>
             <option value='2' " . (($this->content == 2) ? 'selected="selected"' : '') . ">Low Priority</option>
         </select><br>
         <label>Search for images in post excerpt</label><select class='smaller' name='excerpt'>
             <option value='-11' " . (($this->excerpt == -11) ? 'selected="selected"' : '') . ">Don't use</option>
             <option value='-1' " . (($this->excerpt == -1) ? 'selected="selected"' : '') . ">Highest Priority</option>
             <option value='0' " . (($this->excerpt == 0) ? 'selected="selected"' : '') . ">High Priority</option>
             <option value='1' " . (($this->excerpt == 1) ? 'selected="selected"' : '') . ">Medium Priority</option>
             <option value='2' " . (($this->excerpt == 2) ? 'selected="selected"' : '') . ">Low Priority</option>
         </select><br>
         <label>Use the </label><select class='smaller' name='imagenum'>
             <option value='1' " . (($this->imagenum == 1) ? 'selected="selected"' : '') . ">1. found image</option>
             <option value='2' " . (($this->imagenum == 2) ? 'selected="selected"' : '') . ">2. found image</option>
             <option value='3' " . (($this->imagenum == 3) ? 'selected="selected"' : '') . ">3. found image</option>
         </select><br>
         <label>Image Size:</label>
         <span style='color:#888;font-size:0.9em'>Width </span><input class='threedigit' param=0 type='text' value='" . $this->width . "' name='width' /><span style='color:#888;font-size:0.9em;margin-right:10px;'> px</span>
         <span style='color:#888;font-size:0.9em'>Height </span><input class='threedigit' param=0 type='text' value='" . $this->height . "' name='height' /><span style='color:#888;font-size:0.9em;margin-right:10px;'> px</span>
      ";
            echo "
         <input type='hidden' param=1 value='" . $this->data . "' name='" . $this->name . "'>
         <input type='hidden' cname=1 value='wpdreamsImageSettings' name='classname-" . $this->name . "'>
         <div class='triggerer'></div>
        </fieldset>
      </div>";
        }

        function processData() {
            $this->data = str_replace("\n", "", $this->data);
            preg_match("/show:(.*?);/", $this->data, $matches);
            $this->show = $matches[1];
            preg_match("/cache:(.*?);/", $this->data, $matches);
            $this->cache = $matches[1];

            preg_match("/usecustom:(.*?);/", $this->data, $matches);
            if (isset($matches) && isset($matches[1]))
                $this->usecustom = $matches[1];
            else
                $this->usecustom = -1;

            preg_match("/customname:(.*?);/", $this->data, $matches);
            if (isset($matches) && isset($matches[1]))
                $this->customname = $matches[1];
            else
                $this->customname = "";

            preg_match("/featured:(.*?);/", $this->data, $matches);
            $this->featured = $matches[1];
            preg_match("/content:(.*?);/", $this->data, $matches);
            $this->content = $matches[1];
            preg_match("/excerpt:(.*?);/", $this->data, $matches);
            $this->excerpt = $matches[1];
            preg_match("/imagenum:(.*?);/", $this->data, $matches);
            $this->imagenum = $matches[1];
            preg_match("/width:(.*?);/", $this->data, $matches);
            $this->width = $matches[1];
            preg_match("/height:(.*?);/", $this->data, $matches);
            $this->height = $matches[1];
            $this->ret = array();
            $this->ret['show'] = $this->show;
            $this->ret['cache'] = $this->cache;
            $this->ret['width'] = $this->width;
            $this->ret['height'] = $this->height;
            $this->ret['imagenum'] = $this->imagenum;
            $this->ret['customname'] = $this->customname;
            $this->ret['from'] = array(
                $this->usecustom => "usecustom",
                $this->featured => "featured",
                $this->content => "content",
                $this->excerpt => "excerpt"
            );
        }

        final function getData() {
            return $this->data;
        }

        final function getSettings() {
            return $this->ret;
        }

        final function getSelected() {
            return $this->ret;
        }
    }
}
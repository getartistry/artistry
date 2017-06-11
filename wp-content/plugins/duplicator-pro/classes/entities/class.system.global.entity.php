<?php 

/**
 * Global System Enity
 *
 * Standard: Missing
 *
 * @package DUP_PRO
 * @subpackage classes/entities
 * @copyright (c) 2017, Snapcreek LLC
 * @license	https://opensource.org/licenses/GPL-3.0 GNU Public License
 * @since 3.0.0
 *
 * @todo Finish Docs
 */

require_once(DUPLICATOR_PRO_PLUGIN_PATH   . '/classes/entities/class.json.entity.base.php');

abstract class DUP_PRO_Recommended_Fix_Type
{
    const Text = 0;
}

/**
 * @copyright 2016 Snap Creek LLC
 * recommendation_type = Text; parameter1 = {text to display to user}; parameter2=n/a
 */
class DUP_PRO_Recommended_Fix
{		
    public $recommended_fix_type;
    public $error_text = '';
    public $parameter1 = '';
    public $parameter2 = '';
}


class DUP_PRO_System_Global_Entity extends DUP_PRO_JSON_Entity_Base
{	
    const NAME_IN_GLOBALS = 'dup_pro_system_global';
    public $recommended_fixes;

    public static function initialize_plugin_data()
    {
        $system_globals = parent::get_by_type(get_class());
        if (count($system_globals) == 0)
        {
            $system_global = new DUP_PRO_System_Global_Entity();
            $system_global->recommended_fixes = array();
            $system_global->save();
        }
    }

    public function add_recommended_text_fix($error_text, $fix_text)
    {
        if($this->is_text_fix_dupe($fix_text) === false)
        {
            $fix = new DUP_PRO_Recommended_Fix();

            $fix->recommended_fix_type = DUP_PRO_Recommended_Fix_Type::Text;
            $fix->error_text = $error_text;
            $fix->parameter1 = $fix_text;

            array_push($this->recommended_fixes, $fix);
        }
    }

    private function is_text_fix_dupe($fix_text)
    {
        $existing_strings = $this->get_recommended_text_fix_strings();

        $present = false;

        foreach($existing_strings as $existing_string)
        {
            if(strcmp($existing_string, $fix_text) == 0)
            {
                $present = true;
                break;
            }
        }

        return $present;
    }

    public function clear_recommended_fixes()
    {
        unset($this->recommended_fixes);

        $this->recommended_fixes = array();
    }

    public function get_recommended_text_fix_strings()
    {
        $text_fix_strings = array();

        /* @var $$fix DUP_PRO_Recommended_Fix */
        foreach($this->recommended_fixes as $fix)
        {
            if($fix->recommended_fix_type == DUP_PRO_Recommended_Fix_Type::Text)
            {
                array_push($text_fix_strings, $fix->parameter1);
            }
        }

        return $text_fix_strings;
    }

    public static function &get_instance()
    {
        if(isset($GLOBALS[self::NAME_IN_GLOBALS]) == false)
        {
            /* @var $system_globals DUP_PRO_System_Global_Entity */
            $system_global = null;

            $system_globals = DUP_PRO_JSON_Entity_Base::get_by_type(get_class());

            if (count($system_globals) > 0)
            {
                $system_global = $system_globals[0];
            }
            else
            {
                DUP_PRO_LOG::traceError("System Global entity is null!");
            }

            $GLOBALS[self::NAME_IN_GLOBALS] = $system_global;
        }

        return $GLOBALS[self::NAME_IN_GLOBALS];
    }
}

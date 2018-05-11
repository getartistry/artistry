<?php 

 
require_once 'inc/class.dom.php';

$wpAutomaticDom = new wpAutomaticDom(file_get_contents('test.txt'));

 

//$regexMatchs = $wpAutomaticDom->getContentByXPath("//*[@class='user-html']");
//$regexMatchs = $wpAutomaticDom->getContentByXPath('//table[contains(concat (" ", normalize-space(@class), " "), " outer ")]/tbody/tr[2]/td/table/tbody/tr/td[1]');
//$regexMatchs = $wpAutomaticDom->getContentByXPath('/html/body/table/tr/td');
//$regexMatchs = $wpAutomaticDom->getContentByXPath('//table');
//$regexMatchs = $wpAutomaticDom->getContentByXPath('/html/body/table/tr[2]/td/table/tr/td[1]/table/tr[1]/td/table');
//$regexMatchs = $wpAutomaticDom->getContentByXPath('/html/body/table/tbody/tr/td[3]/font[2]',false);
//$regexMatchs = $wpAutomaticDom->getContentByXPath('//*[@id="selDistrict1"]/div[3]/a' , false);

$regexMatchs = $wpAutomaticDom->getContentByClass('subbuzz',false);



////html/body/table/tbody/tr[2]/td/table/tbody/tr/td[1]

print_r($regexMatchs);


?>
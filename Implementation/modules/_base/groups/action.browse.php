<?
Warecorp::addTranslation('/modules/groups/action.browse.php.xml');

if (!isset($this->params["city"])) $this->params["city"] = "1";

$city = Warecorp_Location_City::create($this->params["city"]);
if (!$city->name) $city = Warecorp_Location_City::create(1);
$state = $city->getState();
$country = $state->getCountry();

$allCategoriesObj = new Warecorp_Group_Category_List();
$allCategories = $allCategoriesObj->getList();

$this->view->city = $city;
$this->view->state = $state;
$this->view->country = $country;
$this->view->allCategories = $allCategories;

$this->view->bodyContent = "groups/browse_result.tpl";
/**/

 <?php

class SearchOptions
{
  protected $pdo;

  public function __construct($pdo) {
    $this->pdo = $pdo;
  }

//requestMenuItems: //use count?
  public function requestMenuItems($table, $menuType)
  {
    $table = "`".str_replace("`","``",$table)."`";
    $menuType = "`".str_replace("`","``",$menuType)."`";
    $statement = $this->pdo->prepare(
      "SELECT {$menuType}, COUNT($menuType)
      FROM {$table}
      GROUP by {$menuType}");

    $statement->execute();
    //Fetch as array, need to figure out if object would be better?
    return $statement->fetchAll(PDO::FETCH_NUM);
  }

public function populateFilterMenu($table, $menuType) {
  $menuResults = $this->requestMenuItems($table, $menuType);
  $menuArrayName = "filter_{$menuType}";
  foreach($menuResults as $menuItem) {
    echo '<li><label class="checkbox">';
    echo '<input type="checkbox" name="'.$menuArrayName.'[]" value="'.$menuItem[0].'" ';
    if(isset($_POST[$menuArrayName])){ echo in_array($menuItem[0], $_POST[$menuArrayName]) ? "checked='checked' " : "";}
    echo '>'.$menuItem[0].'</label></li>';
  }
} 

}

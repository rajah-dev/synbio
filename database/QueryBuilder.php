 <?php

class QueryBuilder
{

    protected $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public $fullMYSQLquery;

    const SELECTFROM = array(
        "teamprojects" => "SELECT
            team_name,
            year,
            wiki,
            parts,
            section,
            track,
            location,
            institution,
            project_title,
            abstract,
            medal,
            awards FROM teamprojects ",
        "parts" => "SELECT
            part_name,
            part_type,
            short_desc,
            status,
            sample_status,
            sequence_length,
            uses,
            doc_size,
            creation_date,
            part_id,
            description,
            categories,
            deep_count,
            deep_u_list FROM parts ",
        );

    const SCOPE = array(
        "teamprojects" => "WHERE 
            ((team_name LIKE :searchTerm)
            OR (abstract LIKE :searchTerm)
            OR (project_title LIKE :searchTerm)
            OR (awards LIKE :searchTerm))",
        "parts" => "WHERE ((part_name LIKE :searchTerm)
            OR (short_desc LIKE :searchTerm)
            OR (description LIKE :searchTerm)
            OR (author LIKE :searchTerm)
            OR (notes LIKE :searchTerm)
            OR (source LIKE :searchTerm)
            OR (sequence LIKE :searchTerm)
            OR (categories LIKE :searchTerm))",
        );            

    const CLASSSELECT = array(
        "parts" => 'PartData',
        "teamprojects" => 'ProjectData',
        );

    const COLUMNS = array(
        "parts" => array(
            'part_name',
            'part_type',
            'status',
            'doc_size',
            'sequence_length',
            'uses'
            ),
        "teamprojects" => array(
            'team_name',
            'year',
            'section',
            'track',
            'medal',
            )
        );  



    //addFiltertoRequest: Used by generateFilterRequest to take checkbox array and implode into MYSQL query
    //Update for later: Use placeholders? Or use whitelist?
    //For placeholders, will need to separate out filter name from filterlist (execute separately)
    private function addFiltertoRequest($filterName, $filterArray) { // ex. ("year", [2007, 2008, 2014])
        if (!empty($filterArray)) { 
            $filterList = "'".implode("', '", $filterArray)."'"; // ex. $filterList = "'2007', '2008', '2009'"
            $addRequest = " AND ({$filterName} IN ({$filterList}))"; // ex. " AND (year IN ('2007', '2008', '2009')
            return $addRequest;
        } else {
            return FALSE;
        }
    }

    //generateFilterRequest: Used to generate string with all MYSQL filter requests
    //iterates over array of all filter options
    public function generateFilterRequest($allFilters) {
        $filterRequest = "";
        foreach($allFilters as $filter) {
            $filterRequest .= $this->addFiltertoRequest($filter[0],$filter[1]);
        }
        return $filterRequest;
    }

    public function generateFullRequest($table, $allFilters) {

    $filterRequest = $this->generateFilterRequest($allFilters); //generate filter part of the statement

    //Add request portion for filters to sql request
    $sqlRequest = self::SELECTFROM[$table] . self::SCOPE[$table] . $filterRequest;
    return $sqlRequest;
    }



//buildScope: builds the scope of the search, which column the search term gets searched in
//currently requires searchTerm only for false return if empty
  private function buildScope($searchTerm, $queryField)
  {
    if(!empty($searchTerm)) { //if there is a search term...
      $scopeRequest = ''; //set scope variable
      if (empty($queryField)) { //if no search scope selected...
        //query search term in all of these attributes:
        $scopeRequest .= '((team_name LIKE :searchTerm)
          OR (abstract LIKE :searchTerm)
          OR (project_title LIKE :searchTerm)
          OR (awards LIKE :searchTerm)';
      } else { //if at least one scope selected
        $scopeRequest .= '(('.$queryField[0].' LIKE :searchTerm)';

        //RECODE THIS!
        if (count($queryField) > 1) { //continue, if more than one scope selected
          $arrayInt = 1;
          while(!empty($queryField[$arrayInt])) {
            $scopeRequest .= ' OR ('.$queryField[$arrayInt].' LIKE :searchTerm)';
            $arrayInt = $arrayInt + 1;
          }
        }
        $scopeRequest .= ')';//closes statement
      } return $scopeRequest;
    } else {//if no search term, edit filter request to remove first 'AND'
      return FALSE;
    }
  }


  public function selectFrom($table, $searchTerm, $allFilters)
  {
    $this->$fullMYSQLquery = $this->generateFullRequest($table, $allFilters);

    //placeholder must represent a complete data literal
    //with LIKE, we have to prepare our complete literal first, and then send it to the query the usual way:
    $searchTerm = "%{$searchTerm}%";

    $statement = $this->pdo->prepare($this->$fullMYSQLquery);

    $statement->execute(['searchTerm' => $searchTerm]);
    return $statement->fetchAll(PDO::FETCH_CLASS, self::CLASSSELECT[$table]);
  }



}

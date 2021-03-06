<?php

/**
 * Created by PhpStorm.
 * User: bs
 * Date: 28/04/2016
 * Time: 09:45
 */
require_once(DIRECTORY."/vendor/autoload.php");

class MySQL extends medoo
{
    var $con;
    private $salt = 'asdflkjhASFDLKJ1234';

    public function __construct($config)
    {

        $this->con = new medoo([
            'database_type' => 'mysql',
            'database_name' => $config["database"],
            'server' => $config["host"],
            'username' => $config["username"],
            'password' => $config["password"],
            'charset' => 'utf8'
        ]);
    }

    // ////////////////////// General //////////////////////
    function flip_row_col_array($array) {
        $out = array();
        foreach ($array as  $row_key => $row) {
            foreach($row as $col_key => $col){
                $out[$col_key][$row_key]=$col;
            }
        }
        return $out;
    }

    // //////////////////////  Smaxtec  //////////////////////
	function zero_Smaxtec_alert($smaxtec_user_id)
	{
		$sql = 'UPDATE smaxtec_events SET alert=0 WHERE smaxtec_user_id = ' . $smaxtec_user_id;
        $result = $this->con->query($sql);
		if (!$result)
		{
			return false;
		}
        return $result;
	}
	function get_Samxtec_alert($smaxtec_user_id)
	{
		$sql = 'SELECT count(*) as num FROM smaxtec_events WHERE alert=1 AND smaxtec_user_id = ' . $smaxtec_user_id;
		$alert = $this->con->query($sql);
		if (!$alert) {
			return false;
		}
		$rows = $alert->fetch();
		if ($rows['num']>0)
		{
			return $rows['num'];
		}
		return false;
	}

    function Get_Smaxtec_Events($smaxtec_user_id){
        $sql = "SELECT
		   a.id,
		   a.time,
		   a.type,
		   a.name,
		   a.value,
		   b.img,
		   b.category,
		   b.description
		FROM
		   smaxtec_events as a,
		   smaxtec_events_type as b
		WHERE
		   a.event_id = b.id
		   AND smaxtec_user_id = " . $smaxtec_user_id . "
		ORDER BY
		   a.time DESC;";

        $events = $this->con->query($sql)->fetchAll();

        return $events;
    }

    function Get_Smaxtec_Animal_Avg_Data($smaxtec_user_id)
    {
        // Average Chart
        $sql = "
    SELECT
        -- UNIX_TIMESTAMP(time) as time,
        ROUND(UNIX_TIMESTAMP(time)/(600))*600 as time,
        round(avg(activity),2) as activity,
        round(avg(temperature),2) as temperature
    FROM
        smaxtec_animal
    WHERE
        smaxtec_user_id = " . $smaxtec_user_id . "
    group by
        time
    ORDER BY
    time DESC;";

        $result = $this->con->query($sql)->fetchAll();

        return $result;
    }

    function Get_Smaxtec_Animal_Avg_Activity($smaxtec_user_id,$PeriodBack)
    {
        // Average Chart
    $sql = "SELECT
	a.My_Time as time,
	round(avg(a.My_Activity),1) as activity
	from (
	SELECT
        ROUND(UNIX_TIMESTAMP(time)/(600))*600 as My_Time,
        round(activity -1 ,2) as My_Activity
    FROM
        smaxtec_animal
    WHERE
        smaxtec_user_id = " . $smaxtec_user_id . "
		AND smaxtec_animal.time >  (NOW() - INTERVAL  " . $PeriodBack . " HOUR)
    ORDER BY My_Time DESC
	) as a
	GROUP BY time
	ORDER BY activity DESC;";

        $result = $this->con->query($sql)->fetchAll();
        $table = "";

        foreach ($result as $row):
            $table .= date('Y/m/d H:i',$row['time']).",".$row['activity'].'\n';
        endforeach;

        return $table;
    }

	function Get_Smaxtec_Group_Animal_Avg_Temperature ($group_id = 0, $smaxtec_user_id,$PeriodBack)
	{
		if ($group_id==0 || !is_numeric($group_id)):
			return false;
		endif;
		// Average Chart
		$sql = "SELECT
	a.My_Time as time,
	round(avg(a.My_Temp),2) as temperature
	from (
	SELECT
        ROUND(UNIX_TIMESTAMP(time)/(600))*600 as My_Time,
        round(temperature -1 ,2) as My_Temp
    FROM
        smaxtec_animal sa
	LEFT JOIN smaxtec_animal_info sai on sa.name = sai.name and sa.smaxtec_user_id = sai.smaxtec_user_id
    WHERE
        sa.smaxtec_user_id = " . $smaxtec_user_id . "
        AND sai.group_id = " . $group_id . "
		AND sa.temperature > 35
		AND sa.temperature < 40
		AND sa.time >  (NOW() - INTERVAL  " . $PeriodBack . " HOUR)
    ORDER BY My_Time DESC
	) as a
	GROUP BY time
	ORDER BY temperature DESC;";

//		echo $sql;exit;

		$result = $this->con->query($sql);
		if (!$result):
			return false;
		endif;
		$rows = $this->con->query($sql)->fetchAll();
		$table = "";
		foreach ($rows as $row):
			$table .= date('Y/m/d H:i',$row['time']).",".$row['temperature'].'\n';
		endforeach;

		return $table;
	}

    function Get_Smaxtec_Animal_Avg_Temperature ($smaxtec_user_id,$PeriodBack)
    {
        // Average Chart
        $sql = "SELECT
	a.My_Time as time,
	round(avg(a.My_Temp),2) as temperature
	from (
	SELECT
        ROUND(UNIX_TIMESTAMP(time)/(600))*600 as My_Time,
        round(temperature -1 ,2) as My_Temp
    FROM
        smaxtec_animal
    WHERE
        smaxtec_user_id = " . $smaxtec_user_id . "
		AND temperature > 35
		AND temperature < 40
		AND smaxtec_animal.time >  (NOW() - INTERVAL  " . $PeriodBack . " HOUR)
    ORDER BY My_Time DESC
	) as a
	GROUP BY time
	ORDER BY temperature DESC;";

        $result = $this->con->query($sql)->fetchAll();
        $table = "";

        foreach ($result as $row):
            $table .= date('Y/m/d H:i',$row['time']).",".$row['temperature'].'\n';
        endforeach;

        return $table;
    }

    function Get_Smaxtec_Animal_Data($smaxtec_user_id)
    {
// Per-animal chart
        $sql = "
    SELECT
        UNIX_TIMESTAMP(time) as time,
        activity,
        temperature,
        name
    FROM
        smaxtec_animal
    WHERE
        smaxtec_user_id = " . $smaxtec_user_id . "
    ORDER BY
    time DESC;";

        $result = $this->con->query($sql)->fetchAll();

        $animals_arr = [];
//			print_r($result2);exit;
        foreach ($result as $row):
            if (array_search($row['name'], array_keys($animals_arr)) === false):
                $animals_arr[$row['name']]['name'] = $row['name'];
//		echo 'i am reseting cow '.$row['name'].": ".print_r(array_keys($animals_arr))."\n";
                $animals_arr[$row['name']]['data'] = 'Date,Activity,Temperature\n';
            endif;
            $animals_arr[$row['name']]['data'] .= date('Y/m/d H:i', $row['time']) . "," . $row['activity'] . "," . $row['temperature'] . '\n';
        endforeach;

        return $animals_arr;
    }

    function Get_Smaxtec_Animal_Activity($smaxtec_user_id,$PeriodBack)
    {
// Per-animal chart
        $sql = "
    SELECT
        UNIX_TIMESTAMP(time) as time,
        activity,
        name
    FROM
        smaxtec_animal
    WHERE
        smaxtec_user_id = " . $smaxtec_user_id . "
        AND time >  (NOW() - INTERVAL  " . $PeriodBack . " HOUR)
    ORDER BY
    time DESC;";

        $result = $this->con->query($sql)->fetchAll();

        $animals_arr = [];
        foreach ($result as $row):
            if (array_search($row['name'], array_keys($animals_arr)) === false):
                $animals_arr[$row['name']]['name'] = $row['name'];
                $animals_arr[$row['name']]['data'] = 'Date,Activity\n';
            endif;
            $animals_arr[$row['name']]['data'] .= date('Y/m/d H:i', $row['time']) . "," . $row['activity'] . '\n';
        endforeach;

        return $animals_arr;
    }

    function Get_Smaxtec_Animal_Temperature($smaxtec_user_id, $PeriodBack)
    {
//    CASE WHEN (temperature -1) < 38  THEN 38
//		ELSE round((temperature -1),2.0) END  AS  temperature,

//        CASE WHEN temperature BETWEEN '37' AND '38'  THEN 38
//    ELSE round((temperature -1),2.0) END  AS  temperature,

        //(temperature -0.5) AS temperature,

// Per-animal chart
        $sql = "
    SELECT
        UNIX_TIMESTAMP(time) as time,
        CASE WHEN (temperature -0.5) < 38  THEN 38 ELSE round((temperature -0.5),2.0) END  AS  temperature,
        name
    FROM
        smaxtec_animal
    WHERE
        smaxtec_user_id = " . $smaxtec_user_id . "
        AND time >  (NOW() - INTERVAL  " . $PeriodBack . " HOUR)
    ORDER BY
    name  , time DESC;";

        $result = $this->con->query($sql)->fetchAll();
        $animals_arr = [];

        foreach ($result as $row):
            if (array_search($row['name'], array_keys($animals_arr)) === false):
                $animals_arr[$row['name']]['name'] = $row['name'];
                $animals_arr[$row['name']]['data'] = 'Date,Temperature\n';
            endif;
            $animals_arr[$row['name']]['data'] .= date('Y/m/d H:i', $row['time']) . "," . $row['temperature'] . '\n';
        endforeach;

        return $animals_arr;
    }

	function Get_Smaxtec_Group_Animal_Temperature_Reduction($group_id, $smaxtec_user_id, $PeriodBack)
	{
// Per-animal chart
		$sql = "
    SELECT
        UNIX_TIMESTAMP(time) as time,
        (temperature -1) as  temperature,
        sa.name
    FROM
        smaxtec_animal sa
	LEFT JOIN smaxtec_animal_info sai on sa.name = sai.name and sa.smaxtec_user_id = sai.smaxtec_user_id
    WHERE
        sa.smaxtec_user_id = " . $smaxtec_user_id . "
        AND sai.group_id = " . $group_id . "
        AND sa.time >  (NOW() - INTERVAL  " . $PeriodBack . " HOUR)
    ORDER BY
    sa.name  , sa.time DESC;";
//        echo $sql;exit;

		$result = $this->con->query($sql);
		if (!$result):
			return [];
		endif;
		$rows = $result->fetchAll();
		$animals_arr = [];
		$animals_tmp = [];

		foreach ($rows as $row):
			if (array_search($row['name'], array_keys($animals_arr)) === false):
				$animals_arr[$row['name']]['name'] = $row['name'];
				$animals_arr[$row['name']]['data'] = 'Date,Temperature\n';
			endif;
			$animals_tmp[$row['name']][$row['time']] = $row['temperature'];
//			$animals_tmp[$row['name']][date('Y/m/d H:i', $row['time'])] = $row['temperature'];
		endforeach;
//		print_r($animals_arr);exit;

		$deviation = 0.5;
		foreach ($animals_arr as $animal):
//			if ($animal['name']=='6617'):
//			print_r($animals_tmp[$animal['name']]);exit;
//			endif;
			$i=1;
			$first_temp = ''; $second_temp = ''; $third_temp = '';
//			echo PHP_EOL;
			foreach ($animals_tmp[$animal['name']] as $k=>$v):
				if ($first_temp != '' && $second_temp != '' && $third_temp != ''):
//					echo 'we have all three'.PHP_EOL;
//					echo '1: '.$first_temp['temperature']."\t".'2: '.$second_temp['temperature']."\t".'3: '.$third_temp['temperature'].PHP_EOL;
//					if (($second_temp['temperature']<$third_temp['temperature'])) :
					if ( (abs($second_temp['temperature']-$third_temp['temperature'])<=$deviation) AND (abs($second_temp['temperature']-$first_temp['temperature'])<=$deviation) ):
						$animals_arr[$animal['name']]['data'] .= date('Y/m/d H:i', $second_temp['time']) . "," . $second_temp['temperature'] . '\n';
//							echo 'second-third NO deviation'.PHP_EOL;
					else:
//							echo 'second-third deviation'.PHP_EOL;
						//drop
					endif;
//					else:
//						if (abs($second_temp['temperature']-$first_temp['temperature'])<=$deviation):
//							$animals_arr[$animal['name']]['data'] .= date('Y/m/d H:i', $second_temp['time']) . "," . $second_temp['temperature'] . '\n';
////							echo 'second-first NO deviation'.PHP_EOL;
//						else:
////							echo 'second-first deviation'.PHP_EOL;
//							//drop
//						endif;
//					endif;
				//123456789
				else:
				endif;
//				if (($i%3)==1):
				$first_temp = $second_temp;
				$second_temp = $third_temp;
				$third_temp['temperature'] = $v;
				$third_temp['time'] = $k;
				$i++;
			endforeach;
		endforeach;
//print_r($animals_arr);exit;
		return $animals_arr;
	}

	function Get_Smaxtec_Group_Times($group_id = 0, $smaxtec_user_id = 0)
	{
		$sql = "
    SELECT
       id, name, start_time, end_time, color
    FROM
        smaxtec_animal_group_time
    WHERE
        smaxtec_user_id = " . $smaxtec_user_id . "
        AND group_id = " . $group_id;
//        echo $sql;exit;

		$result = $this->con->query($sql);
		if (!$result):
			return [];
		endif;
		$rows = $result->fetchAll();
		return $rows;
	}

    function Get_Smaxtec_Group($smaxtec_user_id = 0)
    {
        $sql = "
    SELECT
       id, name
    FROM
        smaxtec_animal_group
    WHERE
        smaxtec_user_id = " . $smaxtec_user_id ;
//        echo $sql;exit;

        $result = $this->con->query($sql);
        if (!$result):
            return [];
        endif;
        $rows = $result->fetchAll();
        return $rows;
    }

function Get_Smaxtec_Group_Name($smaxtec_user_id = 0,$id)
    {
        if (!is_numeric($id)):
            return false;
        endif;
        $sql = "SELECT name FROM smaxtec_animal_group WHERE smaxtec_user_id = " . $smaxtec_user_id ." AND id = " .$id;
//        echo $sql;
//        exit;
        $result = $this->con->query($sql);
        if (!$result) {
            echo $this->con->error();
            exit;
        }
        $row = $result->fetchObject();
        return $row;
    }




    function Get_Smaxtec_Animal_Temperature_Reduction($smaxtec_user_id, $PeriodBack)
    {
// Per-animal chart
        $sql = "
    SELECT
        UNIX_TIMESTAMP(time) as time,
        (temperature -1) as  temperature,
        name
    FROM
        smaxtec_animal
    WHERE
        smaxtec_user_id = " . $smaxtec_user_id . "
        AND time >  (NOW() - INTERVAL  " . $PeriodBack . " HOUR)
    ORDER BY
    name  , time DESC;";
//        echo $sql;exit;

        $result = $this->con->query($sql)->fetchAll();
		$animals_arr = [];
		$animals_tmp = [];

		foreach ($result as $row):
			if (array_search($row['name'], array_keys($animals_arr)) === false):
				$animals_arr[$row['name']]['name'] = $row['name'];
				$animals_arr[$row['name']]['data'] = 'Date,Temperature\n';
			endif;
			$animals_tmp[$row['name']][$row['time']] = $row['temperature'];
//			$animals_tmp[$row['name']][date('Y/m/d H:i', $row['time'])] = $row['temperature'];
		endforeach;
//		print_r($animals_arr);exit;

		$deviation = 0.5;
        foreach ($animals_arr as $animal):
//			if ($animal['name']=='6617'):
//			print_r($animals_tmp[$animal['name']]);exit;
//			endif;
			$i=1;
			$first_temp = ''; $second_temp = ''; $third_temp = '';
//			echo PHP_EOL;
			foreach ($animals_tmp[$animal['name']] as $k=>$v):
				if ($first_temp != '' && $second_temp != '' && $third_temp != ''):
//					echo 'we have all three'.PHP_EOL;
//					echo '1: '.$first_temp['temperature']."\t".'2: '.$second_temp['temperature']."\t".'3: '.$third_temp['temperature'].PHP_EOL;
//					if (($second_temp['temperature']<$third_temp['temperature'])) :
						if ( (abs($second_temp['temperature']-$third_temp['temperature'])<=$deviation) AND (abs($second_temp['temperature']-$first_temp['temperature'])<=$deviation) ):
							$animals_arr[$animal['name']]['data'] .= date('Y/m/d H:i', $second_temp['time']) . "," . $second_temp['temperature'] . '\n';
//							echo 'second-third NO deviation'.PHP_EOL;
						else:
//							echo 'second-third deviation'.PHP_EOL;
							//drop
						endif;
//					else:
//						if (abs($second_temp['temperature']-$first_temp['temperature'])<=$deviation):
//							$animals_arr[$animal['name']]['data'] .= date('Y/m/d H:i', $second_temp['time']) . "," . $second_temp['temperature'] . '\n';
////							echo 'second-first NO deviation'.PHP_EOL;
//						else:
////							echo 'second-first deviation'.PHP_EOL;
//							//drop
//						endif;
//					endif;
                //123456789
				else:
				endif;
//				if (($i%3)==1):
					$first_temp = $second_temp;
					$second_temp = $third_temp;
					$third_temp['temperature'] = $v;
					$third_temp['time'] = $k;
				$i++;
			endforeach;
        endforeach;
//print_r($animals_arr);exit;
        return $animals_arr;
    }


    function Get_Smaxtec_Sensor_Avg_Data($smaxtec_user_id)
    {
        $sql = "
    SELECT
        -- UNIX_TIMESTAMP(time) as time,
        ROUND(UNIX_TIMESTAMP(time)/(600))*600 as time,
        round(avg(humidity),2) as humidity,
        round(avg(temperature),2) as temperature
    FROM
        smaxtec_sensor
    WHERE
        smaxtec_user_id = " . $smaxtec_user_id . "
    group by
        time
    ORDER BY
    time DESC;";

        $result = $this->con->query($sql)->fetchAll();

        return $result;
    }


    function Get_Smaxtec_Sensor_Data($smaxtec_user_id)
    {
        $sql = "
            SELECT
                UNIX_TIMESTAMP(time) as time,
                humidity,
                temperature,
                name
            FROM
                smaxtec_sensor
            WHERE
                smaxtec_user_id = " . $smaxtec_user_id . "
            ORDER BY
            time DESC;";

        $result = $this->con->query($sql)->fetchAll();
        $sensors_arr = [];

        foreach ($result as $row):
            if (array_search($row['name'], array_keys($sensors_arr)) === false):
                $sensors_arr[$row['name']]['name'] = $row['name'];
                //		echo 'i am reseting cow '.$row['name'].": ".print_r(array_keys($sensors_arr))."\n";
                $sensors_arr[$row['name']]['data'] = 'Date,humidity,Temperature\n';
            endif;
            $sensors_arr[$row['name']]['data'] .= date('Y/m/d H:i', $row['time']) . "," . $row['humidity'] . ";" . $row['humidity'] . ";" . $row['humidity'] . "," . $row['temperature'] . ";" . $row['temperature'] . ";" . $row['temperature'] . '\n';
        endforeach;

        return $sensors_arr;
    }


    function Get_Smaxtec_Sensor_Temperature($smaxtec_user_id, $PeriodBack)
    {
// Per-animal chart
        $sql = "
    SELECT
        UNIX_TIMESTAMP(time) as time,
        temperature,
        name
    FROM
        smaxtec_sensor
    WHERE
        smaxtec_user_id = " . $smaxtec_user_id . "
        AND time >  (NOW() - INTERVAL  " . $PeriodBack . " HOUR)
    ORDER BY
    time DESC;";

        $result = $this->con->query($sql)->fetchAll();
        $animals_arr = [];

        foreach ($result as $row):
            if (array_search($row['name'], array_keys($animals_arr)) === false):
                $animals_arr[$row['name']]['name'] = $row['name'];
                $animals_arr[$row['name']]['data'] = 'Date,Temperature\n';
            endif;
            $animals_arr[$row['name']]['data'] .= date('Y/m/d H:i', $row['time']) . "," . $row['temperature'] . '\n';
        endforeach;

        return $animals_arr;
    }
    function Get_Smaxtec_Sensor_Humidity($smaxtec_user_id, $PeriodBack)
    {
// Per-animal chart
        $sql = "
    SELECT
        UNIX_TIMESTAMP(time) as time,
        humidity,
        name
    FROM
        smaxtec_sensor
    WHERE
        smaxtec_user_id = " . $smaxtec_user_id . "
        AND time >  (NOW() - INTERVAL  " . $PeriodBack . " HOUR)
    ORDER BY
    time DESC;";

        $result = $this->con->query($sql)->fetchAll();
        $animals_arr = [];

        foreach ($result as $row):
            if (array_search($row['name'], array_keys($animals_arr)) === false):
                $animals_arr[$row['name']]['name'] = $row['name'];
                $animals_arr[$row['name']]['data'] = 'Date,Humidity\n';
            endif;
            $animals_arr[$row['name']]['data'] .= date('Y/m/d H:i', $row['time']) . "," . $row['humidity'] . '\n';
        endforeach;

        return $animals_arr;
    }
    function Get_Smaxtec_Sensor_Avg_Temperature ($smaxtec_user_id,$PeriodBack)
    {
        // Average Chart
         $sql = "SELECT
	a.My_Time as time,
	round(avg(a.My_Temp),2) as temperature
	from (
	SELECT
        ROUND(UNIX_TIMESTAMP(time)/(600))*600 as My_Time,
        round(temperature -1 ,2) as My_Temp
    FROM
        smaxtec_sensor
    WHERE
        smaxtec_user_id = " . $smaxtec_user_id . "
		AND time >  (NOW() - INTERVAL  " . $PeriodBack . " HOUR)
    ORDER BY My_Time DESC
	) as a
	GROUP BY time
	ORDER BY temperature DESC;";


        $result = $this->con->query($sql)->fetchAll();
        $table = "";

        foreach ($result as $row):
            $table .= date('Y/m/d H:i',$row['time']).",".$row['temperature'].'\n';
        endforeach;

        return $table;
    }

    function Get_Smaxtec_Sensor_Avg_Humidity ($smaxtec_user_id,$PeriodBack)
    {
        // Average Chart
        $sql = "SELECT
	a.My_Time as time,
	round(avg(a.My_Humidity),2) as humidity
	from (
	SELECT
        ROUND(UNIX_TIMESTAMP(time)/(600))*600 as My_Time,
        round(humidity -1 ,2) as My_Humidity
    FROM
        smaxtec_sensor
    WHERE
        smaxtec_user_id = " . $smaxtec_user_id . "
		AND time >  (NOW() - INTERVAL  " . $PeriodBack . " HOUR)
    ORDER BY My_Time DESC
	) as a
	GROUP BY time
	ORDER BY humidity DESC;";


        $result = $this->con->query($sql)->fetchAll();
        $table = "";

        foreach ($result as $row):
            $table .= date('Y/m/d H:i',$row['time']).",".$row['humidity'].'\n';
        endforeach;

        return $table;
    }


    // //////////////////////  Strauss  //////////////////////
    function Get_Strauss_Manufacturer_Invoices($StraussUserId,$StartDate,$EndDate)
    {
        // Average Chart
        $sql = "SELECT
                   ProductionDate,
                   PayDate,
                   VatDate,
                   AccountType,
                   InvoiceNum,
                   FromDate,
                   ToDate,
                   TotalPreVat,
                   Vat
                FROM
                   straussmonthbill
                WHERE
                   UserId = '" . $StraussUserId . "'
                   AND ProductionDate >= '" . $StartDate . "'
                   AND ProductionDate <= '" . $EndDate . "'
                ORDER by
                   ProductionDate desc;";

        $result = $this->con->query($sql)->fetchAll();

        $table = "";
        $id = 1;
        foreach ($result as $row):
            $table .= "<tr>
        <td>" . $id . "</td>
        <td>" . $row['ProductionDate'] . "</td>
        <td>" . $row['PayDate'] . "</td>
        <td>" . $row['VatDate'] . "</td>
        <td>" . $row['AccountType'] . "</td>
        <td>" . $row['InvoiceNum'] . "</td>
        <td>" . $row['FromDate'] . "</td>
        <td>" . $row['ToDate'] . "</td>
        <td>" . number_format($row['TotalPreVat']) . "</td>
        <td>" . number_format($row['Vat']) . "</td>
        </tr>
        ";
            $id++;
        endforeach;

        return $table;


    }

    function Get_Strauss_Manufacturer_Profile($StraussUserId,$StartDate,$EndDate)
    {
        // Average Chart
        $sql = "SELECT
                    SampeDate,
                    Dairy,
                    Tanker,
                    DeliveryNumber ,
                    ReportedMilk,
                    ApproveMilk,
                    InvalidMilk,
                    FatPercentage,
                    ProteinPercentage,
                    LactosePercentage,
                    Acidity,
                    Temperature,
                    WaterPercent,
                    Ma,
                    Ta,
                    Mr,
                    Rh,
                    Gz
                FROM
                   straussmanufacturerprofile
                WHERE
                   UserId = " . $StraussUserId . "
                   AND DATE_FORMAT(SampeDate,'%Y-%m-%d') >= '" . $StartDate . "'
                   AND DATE_FORMAT(SampeDate,'%Y-%m-%d') <= '" . $EndDate . "'
                ORDER by
                   SampeDate desc;";

        $result = $this->con->query($sql)->fetchAll();

        $table = "";
        $id = 1;
        foreach ($result as $row):
            $table .= "<tr>
            <td>" . $id . "</td>
            <td>" . $row['SampeDate'] . "</td>
            <td>" . $row['Dairy'] . "</td>
            <td>" . $row['Tanker'] . "</td>
            <td>" . $row['DeliveryNumber'] . "</td>
            <td>" . $row['ReportedMilk'] . "</td>
            <td>" . $row['ApproveMilk'] . "</td>
            <td>" . $row['InvalidMilk'] . "</td>
            <td>" . $row['FatPercentage'] . "</td>
            <td>" . $row['ProteinPercentage'] . "</td>
            <td>" . $row['LactosePercentage'] . "</td>
            <td>" . $row['Acidity'] . "</td>
            <td>" . $row['Temperature'] . "</td>
            <td>" . $row['WaterPercent'] . "</td>
            <td>" . $row['Ma'] . "</td>
            <td>" . $row['Ta'] . "</td>
            <td>" . $row['Mr'] . "</td>
            <td>" . $row['Rh'] . "</td>
            <td>" . $row['Gz'] . "</td>
            </tr>";
            $id++;
        endforeach;

        return $table;


    }

    function Get_Strauss_Milk_Quality_Bacteria($StraussUserId,$StartDate,$EndDate)
    {
        // Average Chart
        $sql = "SELECT
                    Date,
                    Dairy,
                    Count,
                    Average,
                    M,
                    Inspector
                FROM
                   straussmilkqualitybacteria
                WHERE
                   UserId = " . $StraussUserId . "
                   AND DATE_FORMAT(Date,'%Y-%m-%d') >= '" . $StartDate . "'
                   AND DATE_FORMAT(Date,'%Y-%m-%d') <= '" . $EndDate . "'
                ORDER by
                   Date desc;";

        $result = $this->con->query($sql)->fetchAll();

        $table = "";
        $id = 1;
        foreach ($result as $row):
            $table .= "<tr>
            <td>" . $id . "</td>
            <td>" . $row['Date'] . "</td>
            <td>" . $row['Dairy'] . "</td>
            <td>" . $row['Count'] . "</td>
            <td>" . $row['Average'] . "</td>
            <td>" . $row['M'] . "</td>
            <td>" . $row['Inspector'] . "</td>
            </tr>";
            $id++;
        endforeach;

        return $table;
    }

    function Get_Strauss_Milk_Quality_Somatic($StraussUserId,$StartDate,$EndDate)
    {
        // Average Chart
        $sql = "SELECT
                    Date,
                    Dairy,
                    Count,
                    Average,
                    Inspector
                FROM
                   straussmilkqualitysomatic
                WHERE
                   UserId = " . $StraussUserId . "
                   AND DATE_FORMAT(Date,'%Y-%m-%d') >= '" . $StartDate . "'
                   AND DATE_FORMAT(Date,'%Y-%m-%d') <= '" . $EndDate . "'
                ORDER by
                   Date desc;";

        $result = $this->con->query($sql)->fetchAll();

        $table = "";
        $id = 1;
        foreach ($result as $row):
            $table .= "<tr>
            <td>" . $id . "</td>
            <td>" . $row['Date'] . "</td>
            <td>" . $row['Dairy'] . "</td>
            <td>" . $row['Count'] . "</td>
            <td>" . $row['Average'] . "</td>
            <td>" . $row['Inspector'] . "</td>
            </tr>";
            $id++;
        endforeach;

        return $table;
    }

    function Get_Strauss_Marketing_Vs_Cover($StraussUserId,$Year)
    {
        // Average Chart
        $sql = "SELECT
                    Month,
                    MonthlyCover,
                    CumulativeCover,
                    InnovativeMarketing,
                    Cumulative,
                    Supply,
                    A_Quantity,
                    A_Sum,
                    B_Quantity,
                    B_Sum,
                    Total
                FROM
                   straussmarketing_vs_coverpage
                WHERE
                   UserId = " . $StraussUserId . "
                   AND Month like '" . $Year . "-%'
                ORDER by
                   Month desc;";

        $result = $this->con->query($sql)->fetchAll();

        $table = "";
        $id = 1;
        foreach ($result as $row):
            $table .= "<tr>
            <td>" . $id . "</td>
            <td>" . $row['Month'] . "</td>
            <td>" . $row['MonthlyCover'] . "</td>
            <td>" . $row['CumulativeCover'] . "</td>
            <td>" . $row['InnovativeMarketing'] . "</td>
            <td>" . $row['Cumulative'] . "</td>
            <td>" . $row['Supply'] . "</td>
            <td>" . $row['A_Quantity'] . "</td>
            <td>" . $row['A_Sum'] . "</td>
            <td>" . $row['B_Quantity'] . "</td>
            <td>" . $row['B_Sum'] . "</td>
            <td>" . $row['Total'] . "</td>
            </tr>";
            $id++;
        endforeach;

        return $table;
    }

    function Get_Strauss_Consumer_Financial_Concentration($StraussUserId,$Year)
    {
        // Average Chart
        $sql = "SELECT
                    -- Month,
                    CONCAT(SUBSTRING_INDEX(Month, '/',-1),'-',LPAD(SUBSTRING_INDEX(Month, '/',1),2,'0')) as Month,
                    TargetPrice,
                    MilkAmount,
                    MilkSum,
                    FatSum,
                    ProteinSum,
                    BacteriaSum,
                    SomaticSum,
                    CancellationCouncil,
                    AdditionalCharges,
                    TotalCorrections,
                    TotalKosher,
                    LiterCost,
                    TotalPay
                FROM
                   straussconsumerfinancialconcentration
                WHERE
                   UserId = " . $StraussUserId . "
                   AND Month like '%/" . $Year . "'
                ORDER by
                   Id desc;";

        $result = $this->con->query($sql)->fetchAll();

        $table = "";
        $id = 1;
        foreach ($result as $row):
            $table .= "<tr>
            <td>" . $id . "</td>
            <td>" . $row['Month'] . "</td>
            <td>" . $row['TargetPrice'] . "</td>
            <td>" . $row['MilkAmount'] . "</td>
            <td>" . $row['MilkSum'] . "</td>
            <td>" . $row['FatSum'] . "</td>
            <td>" . $row['ProteinSum'] . "</td>
            <td>" . $row['BacteriaSum'] . "</td>
            <td>" . $row['SomaticSum'] . "</td>
            <td>" . $row['CancellationCouncil'] . "</td>
            <td>" . $row['AdditionalCharges'] . "</td>
            <td>" . $row['TotalCorrections'] . "</td>
            <td>" . $row['TotalKosher'] . "</td>
            <td>" . $row['LiterCost'] . "</td>
            <td>" . $row['TotalPay'] . "</td>
            </tr>";
            $id++;
        endforeach;

        return $table;
    }

    // //////////////////////  Tara  //////////////////////
    function Get_Tara_Milk_Delivery($TaraUserId,$StartDate,$EndDate)
    {
        // Average Chart
        $sql = "SELECT
                    SampeDate,
                    DeliveryNumber,
                    ReportedMilk,
                    ActualMilk,
                    FatPercentage,
                    ProteinPercentage,
                    LactosePercentage,
                    WaterPercent,
                    Acidity,
                    Temperature,
                    Site
                FROM
                   taramilkdelivery
                WHERE
                   UserId = " . $TaraUserId . "
                   AND SampeDate >= '" . $StartDate . "'
                   AND SampeDate <= '" . $EndDate . "'
                ORDER by
                   SampeDate desc;";

        $result = $this->con->query($sql)->fetchAll();

        $table = "";
        $id = 1;
        foreach ($result as $row):
            $table .= "<tr>
            <td>" . $id . "</td>
            <td>" . $row['SampeDate'] . "</td>
            <td>" . $row['DeliveryNumber'] . "</td>
            <td>" . $row['ReportedMilk'] . "</td>
            <td>" . $row['ActualMilk'] . "</td>
            <td>" . $row['FatPercentage'] . "</td>
            <td>" . $row['ProteinPercentage'] . "</td>
            <td>" . $row['LactosePercentage'] . "</td>
            <td>" . $row['WaterPercent'] . "</td>
            <td>" . $row['Acidity'] . "</td>
            <td>" . $row['Temperature'] . "</td>
            <td>" . $row['Site'] . "</td>
            </tr>";
            $id++;
        endforeach;

        return $table;
    }

    function Get_Tara_Milk_Quality_Report($TaraUserId,$StartDate,$EndDate)
    {
        // Average Chart
        $sql = "SELECT
                    SampeDate,
                    DeliveryNumber,
                    Somatic,
                    SomaticClassification,
                    Bacteria,
                    BacteriaClassification
                FROM
                   taramilkqualityreport
                WHERE
                   UserId = " . $TaraUserId . "
                   AND SampeDate >= '" . $StartDate . "'
                   AND SampeDate <= '" . $EndDate . "'
                ORDER by
                   SampeDate desc;";

        $result = $this->con->query($sql)->fetchAll();

        $table = "";
        $id = 1;
        foreach ($result as $row):
            $table .= "<tr>
            <td>" . $id . "</td>
            <td>" . $row['SampeDate'] . "</td>
            <td>" . $row['DeliveryNumber'] . "</td>
            <td>" . $row['Somatic'] . "</td>
            <td>" . $row['SomaticClassification'] . "</td>
            <td>" . $row['Bacteria'] . "</td>
            <td>" . $row['BacteriaClassification'] . "</td>
            </tr>";
            $id++;
        endforeach;

        return $table;
    }

    function Get_Tara_Production_Vs_Cover($TaraUserId,$Year)
    {
        // Average Chart
        $sql = "SELECT
                    MonthlyDistribution,
                    CoversPercentage,
                    CoversLiter,
                    ActualAmountMilk,
                    ActualPercentage,
                    DeviationPercentage,
                    Deviation,
                    NonePerformance
                FROM
                   taraproductionvscover
                WHERE
                   UserId = " . $TaraUserId . "
                   AND MonthlyDistribution like '" . $Year . "-%'
                ORDER by
                   MonthlyDistribution desc;";

        $result = $this->con->query($sql)->fetchAll();

        $table = "";
        $id = 1;
        foreach ($result as $row):
            $table .= "<tr>
            <td>" . $id . "</td>
            <td>" . $row['MonthlyDistribution'] . "</td>
            <td>" . $row['CoversPercentage'] . "</td>
            <td>" . $row['CoversLiter'] . "</td>
            <td>" . $row['ActualAmountMilk'] . "</td>
            <td>" . $row['ActualPercentage'] . "</td>
            <td>" . $row['DeviationPercentage'] . "</td>
            <td>" . $row['Deviation'] . "</td>
            <td>" . $row['NonePerformance'] . "</td>
            </tr>";
            $id++;
        endforeach;

        return $table;
    }

    function Get_Tara_Milk_Components($TaraUserId,$Year)
    {
        // Average Chart
        $sql = "SELECT
                    MonthlyDistribution,
                    round(CountryFats,2) as CountryFats,
                    round(TaraFats,2) as TaraFats,
                    round(YazranFats,2) as YazranFats,
                    round(CountryHelbon,2) as CountryHelbon,
                    round(TaraHelbon,2) as TaraHelbon,
                    round(YazranHelbon,2) as YazranHelbon,
                    round(CountrySumatim,2) as CountrySumatim,
                    round(TaraSumatim,2) as TaraSumatim,
                    round(YazranSumatim,2) as YazranSumatim,
                    round(YazranHaidakim,2) as YazranHaidakim
                FROM
                   taramilkcomponents
                WHERE
                   UserId = " . $TaraUserId . "
                   AND MonthlyDistribution like '" . $Year . "-%'
                ORDER by
                   MonthlyDistribution asc;";

        $result = $this->con->query($sql)->fetchAll();

        $table_fat = "";
        foreach ($result as $row):
            $table_fat .= "<tr>
            <td>" . $row['MonthlyDistribution'] . "</td>
            <td>" . $row['CountryFats'] . "</td>
            <td>" . $row['TaraFats'] . "</td>
            <td>" . $row['YazranFats'] . "</td>
            </tr>";
        endforeach;

        $table_protein = "";
        foreach ($result as $row):
            $table_protein .= "<tr>
            <td>" . $row['MonthlyDistribution'] . "</td>
            <td>" . $row['CountryHelbon'] . "</td>
            <td>" . $row['TaraHelbon'] . "</td>
            <td>" . $row['YazranHelbon'] . "</td>
            </tr>";
        endforeach;

        $table_sumatim = "";
        foreach ($result as $row):
            $table_sumatim .= "<tr>
            <td>" . $row['MonthlyDistribution'] . "</td>
            <td>" . $row['CountrySumatim'] . "</td>
            <td>" . $row['TaraSumatim'] . "</td>
            <td>" . $row['YazranSumatim'] . "</td>
            </tr>";
        endforeach;

        $table_haidakim = "";
        foreach ($result as $row):
            $table_haidakim .= "<tr>
            <td>" . $row['MonthlyDistribution'] . "</td>
            <td>" . $row['YazranHaidakim'] . "</td>
            </tr>";
        endforeach;




        return array($table_fat,$table_protein,$table_sumatim,$table_haidakim);
    }

    function Get_Tara_My_Milk_Components($TaraUserId,$Year_1,$Year_2)
    {
        // Average Chart
        $sql = "SELECT
    y1.My_Month as My_Month,
    y1.YazranFats as y1_YazranFats,
    y2.YazranFats as y2_YazranFats,
    y1.YazranHelbon as y1_YazranHelbon,
    y2.YazranHelbon as y2_YazranHelbon,
    y1.YazranSumatim as y1_YazranSumatim,
    y2.YazranSumatim as y2_YazranSumatim,
    y1.YazranHaidakim as y1_YazranHaidakim,
    y2.YazranHaidakim as y2_YazranHaidakim

from
    (
        SELECT
          MonthlyDistribution,
          SUBSTRING_INDEX(MonthlyDistribution, '-', -1) as My_Month,
          round(YazranFats, 2) as YazranFats,
          round(YazranHelbon, 2) as YazranHelbon,
          round(YazranSumatim, 2) as YazranSumatim,
          round(YazranHaidakim, 2) as YazranHaidakim FROM taramilkcomponents
        WHERE
        UserId = " . $TaraUserId . "
        AND MonthlyDistribution like '" . $Year_1 . "-%'
        ORDER by MonthlyDistribution asc
    ) y1 join (
        SELECT
            MonthlyDistribution,
            SUBSTRING_INDEX(MonthlyDistribution, '-', -1) as My_Month,
            round(YazranFats, 2) as YazranFats,
            round(YazranHelbon, 2) as YazranHelbon,
            round(YazranSumatim, 2) as YazranSumatim,
            round(YazranHaidakim, 2) as YazranHaidakim FROM taramilkcomponents
        WHERE
            UserId = " . $TaraUserId . "
            AND MonthlyDistribution like '" . $Year_2 . "-%'
        ORDER by MonthlyDistribution asc ) y2
    on y1.My_Month = y2.My_Month
    ORDER by
    y1.My_Month asc;";

        $result = $this->con->query($sql)->fetchAll();

        $table_fat = "";
        foreach ($result as $row):
            $table_fat .= "<tr>
            <td>" . $row['My_Month'] . "</td>
            <td>" . $row['y1_YazranFats'] . "</td>
            <td>" . $row['y2_YazranFats'] . "</td>
            </tr>";
        endforeach;

        $table_protein = "";
        foreach ($result as $row):
            $table_protein .= "<tr>
            <td>" . $row['My_Month'] . "</td>
            <td>" . $row['y1_YazranHelbon'] . "</td>
            <td>" . $row['y2_YazranHelbon'] . "</td>
            </tr>";
        endforeach;

        $table_sumatim = "";
        foreach ($result as $row):
            $table_sumatim .= "<tr>
            <td>" . $row['My_Month'] . "</td>
            <td>" . $row['y1_YazranSumatim'] . "</td>
            <td>" . $row['y2_YazranSumatim'] . "</td>
            </tr>";
        endforeach;

        $table_haidakim = "";
        foreach ($result as $row):
            $table_haidakim .= "<tr>
            <td>" . $row['My_Month'] . "</td>
            <td>" . $row['y1_YazranHaidakim'] . "</td>
            <td>" . $row['y2_YazranHaidakim'] . "</td>
            </tr>";
        endforeach;




        return array($table_fat,$table_protein,$table_sumatim,$table_haidakim);
    }

    function Get_Tara_Annual_Summary($TaraUserId,$Year)
    {
        // Average Chart
        $sql = "SELECT
                    MonthlyDistribution,
                    MilkPrice,
                    CouncilFees,
                    StandardFatPercentage,
                    FatPrice,
                    StandardProteinPercentage,
                    ProteinPrice,
                    MilkAmount,
                    OverallFatAmount,
                    OverallProteinAmount,
                    BalanceFat,
                    BalanceProtein,
                    BacterialCounts,
                    SumatimLevel,
                    MilkPayment,
                    RelativeFatPayment,
                    RelativeProteinPayment,
                    CouncilPayment,
                    PaymentSupplyMilk,
                    QualityMilk,
                    Kosher,
                    ExtraMilk,
                    PenaltyAbnormalThreshold,
                    OtherChargesAndCredits,
                    TotalOtherDebitsAndCredits,
                    TotalPayments
                FROM
                   taramonthlysummary
                WHERE
                   UserId = " . $TaraUserId . "
                   AND MonthlyDistribution like '%". $Year ."'

                ORDER by
                   Id desc;";

        $result = $this->con->query($sql)->fetchAll();


        $table = array();
        $head = array(
//            "התפלגות חודשית",
            "מחיר חלב",
            "דמי מועצה",
            "אחוז שומן תקני",
            "מחיר שומן",
            "אחוז חלבון תקני",
            "מחיר חלבון",
            "כמות חלב",
            "כמות שומן כוללת",
            "כמות חלבון כוללת",
            "חוסר/עודף שומן",
            "חוסר/עודף חלבון",
            "ספירת חיידקים כללית",
            "רמת תאים סומטים",
            "תשלום חלב",
            "תשלום שומן ביחס לתקן",
            "תשלום חלבון ביחס לתקן",
            "קיזוז דמי מועצה",
            "סה''כ תשלום בגין אספקת חלב",
            "איכות חלב",
            "כשרות",
            "חלב עודף",
            "קנס בגין חריגות בבדיקות סף",
            "חיובים וזיכויים נוספים",
            "סה''כ חיובים וזיכויים אחרים",
            "סה''כ תשלומים");

        array_push($table, $head);

        $data_row = "";
        // 12 month
        for ($i = 1; $i <= 12; $i++) {
            $month_format = str_pad($i,  2, '0',STR_PAD_LEFT)  . "-" . $Year;
            foreach ($result as $row):
                if ( strcmp  ($row['MonthlyDistribution'] , $month_format) == 0  ) {
                    $data_row = array(
//                        $month_format,
                        $row['MilkPrice'],
                        $row['CouncilFees'],
                        $row['StandardFatPercentage'],
                        $row['FatPrice'],
                        $row['StandardProteinPercentage'],
                        $row['ProteinPrice'],
                        $row['MilkAmount'],
                        $row['OverallFatAmount'],
                        $row['OverallProteinAmount'],
                        $row['BalanceFat'],
                        $row['BalanceProtein'],
                        $row['BacterialCounts'],
                        $row['SumatimLevel'],
                        $row['MilkPayment'],
                        $row['RelativeFatPayment'],
                        $row['RelativeProteinPayment'],
                        $row['CouncilPayment'],
                        $row['PaymentSupplyMilk'],
                        $row['QualityMilk'],
                        $row['Kosher'],
                        $row['ExtraMilk'],
                        $row['PenaltyAbnormalThreshold'],
                        $row['OtherChargesAndCredits'],
                        $row['TotalOtherDebitsAndCredits'],
                        $row['TotalPayments']);
                    array_push($table, $data_row);
                }
                endforeach;
        }

        // Yearly summery
        foreach ($result as $row):
            if ( strcmp  ($row['MonthlyDistribution'] , $Year) == 0  ) {
                $data_row = array(
//                    $Year,
                    $row['MilkPrice'],
                    $row['CouncilFees'],
                    $row['StandardFatPercentage'],
                    $row['FatPrice'],
                    $row['StandardProteinPercentage'],
                    $row['ProteinPrice'],
                    $row['MilkAmount'],
                    $row['OverallFatAmount'],
                    $row['OverallProteinAmount'],
                    $row['BalanceFat'],
                    $row['BalanceProtein'],
                    $row['BacterialCounts'],
                    $row['SumatimLevel'],
                    $row['MilkPayment'],
                    $row['RelativeFatPayment'],
                    $row['RelativeProteinPayment'],
                    $row['CouncilPayment'],
                    $row['PaymentSupplyMilk'],
                    $row['QualityMilk'],
                    $row['Kosher'],
                    $row['ExtraMilk'],
                    $row['PenaltyAbnormalThreshold'],
                    $row['OtherChargesAndCredits'],
                    $row['TotalOtherDebitsAndCredits'],
                    $row['TotalPayments']);
                array_push($table, $data_row);
            }
        endforeach;

        $flip_table = $this->flip_row_col_array($table);

        $table ="";
        foreach ($flip_table as  $row_key => $row){
            $table .= "<tr>";
            foreach($row as $col_key => $col){
                $table .= "<td>" . $col . "</td>";
            }
            $table .= "</tr>";

        }

    return $table;
    }

    function Get_Tara_Invoice_Summary($TaraUserId,$StartDate,$EndDate)
    {
        // Average Chart
        $sql = "SELECT
                    BilNum,
                    Product,
                    Quantity,
                    Unit,
                    DeliveryDate,
                    Total
                FROM
                   tarabill
                WHERE
                   UserId = " . $TaraUserId . "
                   AND DeliveryDate >= '" . $StartDate . "'
                   AND DeliveryDate <= '" . $EndDate . "'
                ORDER by
                   DeliveryDate desc;";

        $result = $this->con->query($sql)->fetchAll();

        $table = "";
        $id = 1;
        foreach ($result as $row):
            $table .= "<tr>
            <td>" . $id . "</td>
            <td>" . $row['BilNum'] . "</td>
            <td>" . $row['Product'] . "</td>
            <td>" . $row['Quantity'] . "</td>
            <td>" . $row['Unit'] . "</td>
            <td>" . $row['DeliveryDate'] . "</td>
            <td>" . $row['Total'] . "</td>
            </tr>";
            $id++;
        endforeach;

        return $table;
    }

    function Get_Tara_Mehadrin_Calculation($TaraUserId,$Year)
    {
        // Average Chart
        $sql = "SELECT
                    -- MonthlyDistribution,
                    CONCAT(SUBSTRING_INDEX(MonthlyDistribution, '-',1),'-',LPAD(SUBSTRING_INDEX(MonthlyDistribution, '-',-1),2,'0')) as MonthlyDistribution,
                    AnnualCap,
                    MonthlyCover,
                    MehadrinDairyTariff,
                    MonthlyAmount,
                    PercentApproval,
                    Differences,
                    TotalMonthlyCredit
                FROM
                   taramehadrindairycalculation
                WHERE
                   UserId = " . $TaraUserId . "
                   AND MonthlyDistribution like '" . $Year . "-%'
                ORDER by
                   MonthlyDistribution desc;";

        $result = $this->con->query($sql)->fetchAll();

        $table = "";
        $id = 1;
        foreach ($result as $row):
            $table .= "<tr>
            <td>" . $id . "</td>
            <td>" . $row['MonthlyDistribution'] . "</td>
            <td>" . $row['AnnualCap'] . "</td>
            <td>" . $row['MonthlyCover'] . "</td>
            <td>" . $row['MehadrinDairyTariff'] . "</td>
            <td>" . $row['MonthlyAmount'] . "</td>
            <td>" . $row['PercentApproval'] . "</td>
            <td>" . $row['Differences'] . "</td>
            <td>" . $row['TotalMonthlyCredit'] . "</td>
            </tr>";
            $id++;
        endforeach;

        return $table;
    }

    function Tara_Parameters_Dairy_Council($TaraUserId,$Year)
    {
        // Average Chart
        $sql = "SELECT
                    CONCAT(SUBSTRING_INDEX(MonthlyDistribution, '-',1),'-',LPAD(SUBSTRING_INDEX(MonthlyDistribution, '-',-1),2,'0')) as MonthlyDistribution,
                    Limit_Excess_Sebum_A,
                    TargetPrice,
                    Council_Service_Fees,
                    Collection_Percentage_Excess_Sebum_A,
                    Her_Rate_Per_Liter_Excess_A,
                    Collection_Percentage_Excess_Sebum_B,
                    Her_Rate_Per_Liter_Excess_B
                FROM
                   taratableparametersdairycouncil
                WHERE
                   UserId = " . $TaraUserId . "
                   AND MonthlyDistribution like '" . $Year . "-%'
                ORDER by
                   MonthlyDistribution desc;";

        $result = $this->con->query($sql)->fetchAll();

        $table = "";
        $id = 1;
        foreach ($result as $row):
            $table .= "<tr>
            <td>" . $id . "</td>
            <td>" . $row['MonthlyDistribution'] . "</td>
            <td>" . $row['Limit_Excess_Sebum_A'] . "</td>
            <td>" . $row['TargetPrice'] . "</td>
            <td>" . $row['Council_Service_Fees'] . "</td>
            <td>" . $row['Collection_Percentage_Excess_Sebum_A'] . "</td>
            <td>" . $row['Her_Rate_Per_Liter_Excess_A'] . "</td>
            <td>" . $row['Collection_Percentage_Excess_Sebum_B'] . "</td>
            <td>" . $row['Her_Rate_Per_Liter_Excess_B'] . "</td>
            </tr>";
            $id++;
        endforeach;

        return $table;
    }

    function Get_Tara_Monthly_Quota_Procedure($TaraUserId,$Year)
    {
        // Average Chart
        $sql = "SELECT
                    CONCAT(SUBSTRING_INDEX(MonthlyDistribution, '-',1),'-',LPAD(SUBSTRING_INDEX(MonthlyDistribution, '-',-1),2,'0')) as MonthlyDistribution,
                    quota,
                    Production,
                    Balance,
                    Excess_Sebum_A,
                    Excess_Sebum_B,
                    Balance_A,
                    Balance_B,
                    Total_Balance
                FROM
                   taramonthlyquotaprocedure
                WHERE
                   UserId = " . $TaraUserId . "
                   AND MonthlyDistribution like '" . $Year . "-%'
                ORDER by
                   MonthlyDistribution desc;";

        $result = $this->con->query($sql)->fetchAll();

        $table = "";
        $id = 1;
        foreach ($result as $row):
            $table .= "<tr>
            <td>" . $id . "</td>
            <td>" . $row['MonthlyDistribution'] . "</td>
            <td>" . $row['quota'] . "</td>
            <td>" . $row['Production'] . "</td>
            <td>" . $row['Balance'] . "</td>
            <td>" . $row['Excess_Sebum_A'] . "</td>
            <td>" . $row['Excess_Sebum_B'] . "</td>
            <td>" . $row['Balance_A'] . "</td>
            <td>" . $row['Balance_B'] . "</td>
            <td>" . $row['Total_Balance'] . "</td>
            </tr>";
            $id++;
        endforeach;

        return $table;
    }

	function User_register($user_name, $pass, $first_name, $last_name, $image, $role, $language, $bi_user, $bi_pass)
	{
		$sql = "INSERT INTO user (`username`, `password`, `first_name`, `last_name`, `image`, `role`, `language` ,`bi_user`, `bi_pass`) "
			."VALUES('".$user_name."',sha1('".$this->salt.$pass."'),'".$first_name."','".$last_name."',LOAD_FILE('".$image."'),'".$role."','".$language."','".$bi_user."','".$bi_pass."')";
//        echo $sql;exit;
		$result = $this->con->query($sql);
		if (!$result) {

			return false;
		}
		else return true;
	}

	function User_update($user_id, $user_name, $pass, $first_name, $last_name, $image, $role, $language, $bi_user, $bi_pass)
	{
		if ($user_id==0):
			return false;
		endif;
		$sql = "UPDATE user set ";
		if ($user_name!=''):
			$sql_params []= "`username`='".$user_name."'";
		endif;
		if ($pass!=''):
			$sql_params []= "`password`=sha1('".$this->salt.$pass."')";
		endif;
		$sql_params []= "`first_name`='".$first_name."'";
		$sql_params []= "`last_name`='".$last_name."'";
		if ($image!=''):
			$sql_params []= "`image`=LOAD_FILE('".$image."')";
//			$sql_params []= "`image`=LOAD_FILE('yada')";
		endif;
		if ($role!=''):
			$sql_params []="`role`='".$role."'";
		endif;
		if ($language!=''):
			$sql_params []= "`language`='".$language."'";
		endif;
		if ($bi_user!=''):
			$sql_params []= "`bi_user`='".$bi_user."'";
		endif;
		if ($bi_pass!=''):
			$sql_params []= "`bi_pass`='".$bi_pass."'";
		endif;
		$sql .= implode(',',$sql_params);
		$sql .= ' WHERE `id`='.$user_id;
//echo $sql;exit;
		$result = $this->con->query($sql);
		if (!$result) {
//			echo $this->con->error();exit;
			return false;
		}
		return true;
	}

    function User_ProfileUpdate($id,$pass, $first_name, $last_name, $image, $language)
    {

        $sql = "UPDATE  user "
        . "SET "
        ." password  = sha1('".$this->salt.$pass."') ,"
        ." first_name = '".$first_name."' ,"
        ." last_name = '".$last_name."' ,"
        ." image = ' . LOAD_FILE('".$image."'),"
        ." language = " . $language . " ,"
        ." WHERE `user`.`id` =  . $id .";

//        $sql = "INSERT INTO user (`password`, `first_name`, `last_name`, `image`, `language` ) "
//            ."VALUES(sha1('".$this->salt.$pass."'),'".$first_name."','".$last_name."',LOAD_FILE('".$image."'),'".$language."')";

        $result = $this->con->query($sql);
        if (!$result) {

            return false;
        }
        else return true;
    }

    public function User_cookie_login($cookie)
	{
		$sql = "SELECT username FROM user WHERE usercookie='".sha1($this->salt.$cookie)."'";
		$result = $this->con->query($sql);
		if (!$result) {
			print_r($this->con->error());
			exit;
		}
		$row=$result->fetch();
		if ($row['username']!=''):
			$this->User_login($row['username']);
			return true;
		endif;
		return false;
	}

    public function User_pass_login($user, $pass, $stay_signed_in)
    {
        $sql = "SELECT username, password FROM user WHERE username='$user'";
        $result = $this->con->query($sql);
		if (!$result) {
			print_r($this->con->error());
			exit;
		}

        $row=$result->fetch();
//		print_r($row);exit;
//        echo $user.' -b- '.$row['password'] .' -a- ' . sha1($this->salt.$pass) .' -b- '.$pass."<br />".EOL;
        if($row['password']==sha1($this->salt.$pass))
        {
            $this->User_login($row['username']);
			if ($stay_signed_in) {
				// set cookie random number
				$rand_num = sha1(rand(0,10000));
				setcookie("email_token", $rand_num, time()+(60*60*24*30), '/', null, null, true); // 30 days
				$sql = "UPDATE user SET `usercookie`='".sha1($this->salt.$rand_num)
					."' WHERE username='".$row['username']."'";
				$result = $this->con->query($sql);
				if (!$result) {
					print_r($this->con->error());
					exit;
				}
			}
            return true;
        }
        return false;
    }

	private function User_login($user)
	{
        list ($id, $first_name, $last_name, $language, $privileges, $bi_user , $bi_pass, $image) = $this->User_Get_Details_By_UserName($user);
        $_SESSION['user'] = $user;
        $_SESSION['user_id'] = $id;
        $_SESSION['first_name'] = $first_name;
        $_SESSION['last_name'] = $last_name;
        $_SESSION['language'] = $language;
        $_SESSION['privileges'] = $privileges;
        $_SESSION['image'] = $image;
        $_SESSION['bi_user'] = $bi_user;
        $_SESSION['bi_pass'] = $bi_pass;

        $farms =  $this->GetMyFarm($_SESSION['user_id']);
        if (empty($farms)):
            $_SESSION['my_farm'] = "";
            $this->Reset_Farm_Credentials();
        else:
        $_SESSION['my_farm'] = $farms[0];
        $this->Set_Farm_Credentials($farms[0]['id']);
        endif;
	}

    public function User_reset_env($user)
    {
        unset ($_SESSION['user']);
        unset ($_SESSION['user_id']);
        unset ($_SESSION['first_name']);
        unset ($_SESSION['last_name']);
        unset ($_SESSION['language']);
        unset ($_SESSION['privileges']);
        unset ($_SESSION['image']);
        unset ($_SESSION['pass']);
        unset ($_SESSION['my_farm']);
        unset ($_SESSION['bi_user']);
        unset ($_SESSION['bi_pass']);

        $this->User_login($user);
        $this->Reset_Farm_Credentials();

        return true;
    }

    public function Set_Farm_Credentials($farm_id)
    {
        $farm_details = (array)$this->Farm_Get_Details($farm_id);

        $_SESSION['name']  = $farm_details['name'];
        $_SESSION['farm_id']  = $farm_details['id'];

        $_SESSION['diary_milk_username'] = $farm_details['diary_milk_username'];
        $_SESSION['diary_milk_type'] = $farm_details['diary_milk_type'];
        $_SESSION['diary_milk_enable'] = $farm_details['diary_milk_enable'];

        $_SESSION['sensor_username'] = $farm_details['sensor_username'];
        $_SESSION['sensor_type'] = $farm_details['sensor_type'];
        $_SESSION['sensor_enable'] = $farm_details['sensor_enable'];

        $_SESSION['feed_center_username'] = $farm_details['feed_center_username'];
        $_SESSION['feed_center_type'] = $farm_details['feed_center_type'];
        $_SESSION['feed_center_enable'] = $farm_details['feed_center_enable'];

        $_SESSION['bacarit_username'] = $farm_details['bacarit_username'];
        $_SESSION['bacarit_type'] = $farm_details['bacarit_type'];
        $_SESSION['bacarit_enable'] = $farm_details['bacarit_enable'];

        $_SESSION['milk_production_username'] = $farm_details['milk_production_username'];
        $_SESSION['milk_production_type'] = $farm_details['milk_production_type'];
        $_SESSION['milk_production_enable'] = $farm_details['milk_production_enable'];

        $_SESSION['data_entry_enable'] = $farm_details['data_entry_enable'];
        $_SESSION['analytics_enable'] = $farm_details['analytics_enable'];
    }

    public function Reset_Farm_Credentials()
    {
        unset ($_SESSION['name']);

        unset ($_SESSION['diary_milk_username']);
        unset ($_SESSION['diary_milk_type']);
        unset ($_SESSION['diary_milk_enable']);

        unset ($_SESSION['sensor_username']);
        unset ($_SESSION['sensor_type']);
        unset ($_SESSION['sensor_enable']);

        unset ($_SESSION['feed_center_username']);
        unset ($_SESSION['feed_center_type']);
        unset ($_SESSION['feed_center_enable']);

        unset ($_SESSION['bacarit_username']);
        unset ($_SESSION['bacarit_type']);
        unset ($_SESSION['bacarit_enable']);

        unset ($_SESSION['milk_production_username']);
        unset ($_SESSION['milk_production_type']);
        unset ($_SESSION['milk_production_enable']);

        unset ($_SESSION['data_entry_enable']);
        unset ($_SESSION['analytics_enable']);
    }




    function User_check_logged_in()
    {
        session_start();

        if(!isset($_SESSION['user']))
        {
            header("Location: index.php");
        }
        $res=mysql_query("SELECT * FROM user WHERE user_id=".$_SESSION['user']);
        $userRow=mysql_fetch_array($res);
    }

	public function User_get_reset_pass_token($user)
	{

		$sql = "SELECT username, password FROM user WHERE username='$user'";
		$result = $this->con->query($sql);
		if (!$result) {
			print_r($this->con->error());
			exit;
		}

		$row=$result->fetch();
		$rand_num = sha1(rand(0,10000));
		$reset_pass_token = sha1($this->salt.$rand_num);
		$sql = "UPDATE user SET `resetpass`='".$reset_pass_token."' WHERE username='".$row['username']."'";
		$result = $this->con->query($sql);
		if (!$result) {
			print_r($this->con->error());
			exit;
		}
		return sha1($this->salt.$rand_num);
	}

	public function User_set_pass_token($token, $newpass)
	{
		if ($token==''):
			return false;
		endif;
		$sql = "SELECT username FROM user WHERE resetpass='".mysql_real_escape_string($token)."'";
		$result = $this->con->query($sql);
		if (!$result) {
			print_r($this->con->error());
			exit;
		}
		$row=$result->fetch();
		$sql = "UPDATE user SET `resetpass`='', `password`=sha1('".$this->salt.$newpass."') WHERE username='".$row['username']."'";
		$result = $this->con->query($sql);
		if (!$result) {
			print_r($this->con->error());
			exit;
		}
		return true;

	}

    function User_Get_Details($user_id)
    {
		if (!is_numeric($user_id)):
			return false;
		endif;
        $sql = "SELECT * FROM  user  WHERE id=$user_id";
//        echo $sql;
//        exit;
        $result = $this->con->query($sql);
        if (!$result) {
			echo $this->con->error();
			exit;
		}

        $row = $result->fetchObject();
//        echo '<pre>' . print_r(get_defined_vars(), true) . '</pre>';
//            exit;


//            echo mysql_error();
//            exit;
//        return array($row['username'],$row['first_name'],$row['last_name'],$row['language'],$row['image']);
        return $row;
    }

    function User_Get_Bi_Details_By_UserName($id)
    {

        $sql = "SELECT bi_user, bi_pass FROM  user  WHERE id = '" . $id . "'";
//        echo $sql;
//        exit;
        $result = $this->con->query($sql);
        if (!$result) {
            echo $this->con->error();
            exit;
        }

        $row = $result->fetchObject();

        return array($row->bi_user,$row->bi_pass);
    }

    function User_Get_Details_By_UserName($username)
    {

        $sql = "SELECT id, first_name, last_name , language, role, bi_user, bi_pass , image  FROM  user  WHERE username = '" . $username . "'";
//        echo $sql;
//        exit;
        $result = $this->con->query($sql);
        if (!$result) {
            echo $this->con->error();
            exit;
        }

        $row = $result->fetchObject();
//        echo '<pre>' . print_r(get_defined_vars(), true) . '</pre>';
//            exit;


//            echo mysql_error();
//            exit;
//        return array($row['username'],$row['first_name'],$row['last_name'],$row['language'],$row['image']);
		if (!isset($row->id)):
			return false;
		endif;
        return array($row->id,$row->first_name,$row->last_name,$row->language,$row->role, $row->bi_user, $row->bi_pass, $row->image);
    }

    function User_Get_User_Id($username)
    {
        $sql = "SELECT id FROM  user  WHERE username = '" . $username . "'";
//        echo $sql;
//        exit;

        $result = $this->con->query($sql);
        if (!$result) {
            echo $this->con->error();
            exit;
        }

        $row = $result->fetchObject();

        return ($row->id);
    }

	function User_Message($userid, $msg)
	{
		if ($userid>0 && $msg!='')
		{
			$sql = "INSERT INTO user_board (`user_id`, `msg`) VALUES(".$userid.",'".$msg."')";
//        echo $sql;exit;
			$result = $this->con->query($sql);
			if (!$result) {
//				print_r($this->con->error());
//				exit;
				return false;
			}
			return true;
		}
		return false;
	}

	function Get_Messages_Num()
	{
		$sql = "SELECT count(1) as num FROM user_board";
		$result = $this->con->query($sql);
		if (!$result) {
			print_r($this->con->error());
			exit;
		}
		$row = $result->fetchObject();
		return $row->num;
	}

	function Get_Messages($page = 0)
	{
		$num_of_msgs_in_page = 15;
		$sql = "SELECT * FROM user_board";
//        exit;
		if ($page>0):
			$sql .= ' LIMIT '.($num_of_msgs_in_page)*($page-1).','.$num_of_msgs_in_page;
		endif;
		$result = $this->con->query($sql);
		if (!$result) {
			print_r($this->con->error());
			exit;
		}

//		$row = $result->fetchObject();
//        echo '<pre>' . print_r(get_defined_vars(), true) . '</pre>';
//            exit;


//            echo mysql_error();
//            exit;
//        return array($row['username'],$row['first_name'],$row['last_name'],$row['language'],$row['image']);
		return $result;
	}

	function User_Delete_Message($msg_id)
	{

		$sql = 'DELETE FROM user_board WHERE id='.$msg_id;
		$result = $this->con->query($sql);
		return $result;
	}


    function get_error()
	{
		return $this->con->error();
	}

    // /////////////////////////////// Data Entry ///////////////////////////////////////
    function Get_Milk_Measurement($FarmId,$StartDate,$FinishDate)
    {
        // Average Chart
        $sql = "SELECT
					id,
                    sample_date,
                    morning_compute,
                    morning_measure,
                    morning_quantity,
                    noon_compute,
                    noon_measure,
                    noon_quantity,
                    evening_compute,
                    eveningmeasure,
                    evening_quantity,
                    total_compute,
                    milk_antibiotics,
                    tank_liter,
                    tank_kg,
                    deviation_percent
                FROM
                   data_entry_milk_measurement
                WHERE
                   farm_id = " . $FarmId . "
                   AND sample_date >= '" . $StartDate . "'
                   AND sample_date <= '" . $FinishDate . "'
                ORDER by
                   sample_date desc;";

        $result = $this->con->query($sql)->fetchAll();

        $table = "";
		foreach ($result as $row):
			$table .= "<tr data-indexid=\"".$row['id']."\">
            <td data-tdid=\"sample_date\">" . $row['sample_date'] . "</td>
            <td data-tdid=\"morning_compute\">" . $row['morning_compute'] . "</td>
            <td data-tdid=\"morning_measure\">" . $row['morning_measure'] . "</td>
            <td data-tdid=\"morning_quantity\">" . $row['morning_quantity'] . "</td>
            <td data-tdid=\"noon_compute\">" . $row['noon_compute'] . "</td>
            <td data-tdid=\"noon_measure\">" . $row['noon_measure'] . "</td>
            <td data-tdid=\"noon_quantity\">" . $row['noon_quantity'] . "</td>
            <td data-tdid=\"evening_compute\">" . $row['evening_compute'] . "</td>
            <td data-tdid=\"eveningmeasure\">" . $row['eveningmeasure'] . "</td>
            <td data-tdid=\"evening_quantity\">" . $row['evening_quantity'] . "</td>
            <td data-tdid=\"total_compute\">" . $row['total_compute'] . "</td>
            <td data-tdid=\"milk_antibiotics\">" . $row['milk_antibiotics'] . "</td>
            <td data-tdid=\"tank_liter\">" . $row['tank_liter'] . "</td>
            <td data-tdid=\"tank_kg\">" . $row['tank_kg'] . "</td>
            <td data-tdid=\"deviation_percent\">" . $row['deviation_percent'] . "</td>
            </tr>";
        endforeach;

        return $table;
    }

	/**
	 * @param $farmid
	 * @param $rowid
	 * @param $key
	 * @param $value
	 */
	public function Update_Milk_Measurement($farmid, $rowid, $key, $value)
	{
//		$sql = "INSERT INTO milk_measurement (sample_date) VALUES ()";
		$milk_measurement_keys  = array('morning_compute','morning_measure','morning_quantity','noon_compute','noon_measure','noon_quantity','evening_compute','eveningmeasure','evening_quantity','total_compute','milk_antibiotics','tank_liter','tank_kg','deviation_percent');
		if (array_search($key,$milk_measurement_keys) === FALSE)
			return false;
		if (!is_numeric($farmid) || !is_numeric($rowid))
			return false;
		$sql = "UPDATE data_entry_milk_measurement set ".$key."='".mysql_real_escape_string($value)."' WHERE farm_id='".$farmid."' AND id='".$rowid."'";

		$result = $this->con->query($sql);
		if (!$result) {

			return false;
		}
		return true;
	}

	// /////////////////////////////// Data Entry ///////////////////////////////////////
	function Get_Soler_Measurement($FarmId,$StartDate,$FinishDate)
	{
		// Average Chart
		$sql = "SELECT
					id,
                    sample_date,
                    engine_hours,
                    liter
                FROM
                   data_entry_soler_measurement
                WHERE
                   farm_id = " . $FarmId . "
                   AND sample_date >= '" . $StartDate . "'
                   AND sample_date <= '" . $FinishDate . "'
                ORDER by
                   sample_date desc;";

		$result = $this->con->query($sql);
		if (!$result):
			print_r($this->con->error());
			exit;
		endif;
		$result = $result->fetchAll();
		$table = "";
		foreach ($result as $row):
			$table .= "<tr data-indexid=\"".$row['id']."\">
            <td data-tdid=\"sample_date\">" . $row['sample_date'] . "</td>
            <td data-tdid=\"engine_hours\">" . $row['engine_hours'] . "</td>
            <td data-tdid=\"liter\">" . $row['liter'] . "</td>
            </tr>";
		endforeach;

		return $table;
	}

	/**
	 * @param $farmid
	 * @param $rowid
	 * @param $key
	 * @param $value
	 */
	public function Update_Soler_Measurement($farmid, $rowid, $key, $value)
	{
//		$sql = "INSERT INTO milk_measurement (sample_date) VALUES ()";
		$milk_measurement_keys  = array('engine_hours','liter');
		if (array_search($key,$milk_measurement_keys) === FALSE)
			return false;
		if (!is_numeric($farmid) || !is_numeric($rowid))
			return false;
		$sql = "UPDATE data_entry_soler_measurement set ".$key."='".mysql_real_escape_string($value)."' WHERE farm_id='".$farmid."' AND id='".$rowid."'";

		$result = $this->con->query($sql);
		if (!$result) {

			return false;
		}
		return true;
	}
	// /////////////////////////////// Data Entry ///////////////////////////////////////
	function Get_Marketing_Measurement($FarmId,$StartDate,$FinishDate)
	{
		// Average Chart
		$sql = "SELECT
					id,
                    sample_date,
                    marketing_monthly_days,
                    food_monthly_days
                FROM
                   data_entry_marketing_measurement
                WHERE
                   farm_id = " . $FarmId . "
                   AND sample_date >= '" . $StartDate . "'
                   AND sample_date <= '" . $FinishDate . "'
                ORDER by
                   sample_date desc;";

		$result = $this->con->query($sql);
		if (!$result):
			print_r($this->con->error());
			exit;
		endif;
		$result = $result->fetchAll();
		$table = "";
		foreach ($result as $row):
			$table .= "<tr data-indexid=\"".$row['id']."\">
            <td data-tdid=\"sample_date\">" . $row['sample_date'] . "</td>
            <td data-tdid=\"marketing_monthly_days\">" . $row['marketing_monthly_days'] . "</td>
            <td data-tdid=\"food_monthly_days\">" . $row['food_monthly_days'] . "</td>
            </tr>";
		endforeach;

		return $table;
	}

    /**
     * @param $farmid
     * @param $rowid
     * @param $key
     * @param $value
     */
    public function Update_Marketing_Measurement($farmid, $rowid, $key, $value)
    {
//		$sql = "INSERT INTO milk_measurement (sample_date) VALUES ()";
        $milk_measurement_keys  = array('marketing_monthly_days','food_monthly_days');
        if (array_search($key,$milk_measurement_keys) === FALSE)
            return false;
        if (!is_numeric($farmid) || !is_numeric($rowid))
            return false;
        $sql = "UPDATE data_entry_marketing_measurement set ".$key."='".mysql_real_escape_string($value)."' WHERE farm_id='".$farmid."' AND id='".$rowid."'";

        $result = $this->con->query($sql);
        if (!$result) {

            return false;
        }
        return true;
    }

    function Get_Herd($FarmId,$StartDate,$FinishDate)
    {
        // Average Chart
        $sql = "SELECT
					id,
                    sample_date,
                    num_milked,
                    average_liters_per_cow_per_day,
                    average_liters_per_milked_cow_per_day,
                    num_cows,
                    dry,
                    calves,
                    dry_matter_weight_dairy_dish
                FROM
                   data_entry_herd
                WHERE
                   farm_id = " . $FarmId . "
                   AND sample_date >= '" . $StartDate . "'
                   AND sample_date <= '" . $FinishDate . "'
                ORDER by
                   sample_date desc;";

        $result = $this->con->query($sql);
        if (!$result):
            print_r($this->con->error());
            exit;
        endif;
        $result = $result->fetchAll();
        $table = "";
        foreach ($result as $row):
            $table .= "<tr data-indexid=\"".$row['id']."\">
            <td data-tdid=\"sample_date\">" . $row['sample_date'] . "</td>
            <td data-tdid=\"num_milked\">" . $row['num_milked'] . "</td>
            <td data-tdid=\"average_liters_per_cow_per_day\">" . $row['average_liters_per_cow_per_day'] . "</td>
            <td data-tdid=\"average_liters_per_milked_cow_per_day\">" . $row['average_liters_per_milked_cow_per_day'] . "</td>
            <td data-tdid=\"num_cows\">" . $row['num_cows'] . "</td>
            <td data-tdid=\"dry\">" . $row['dry'] . "</td>
            <td data-tdid=\"calves\">" . $row['calves'] . "</td>
            <td data-tdid=\"dry_matter_weight_dairy_dish\">" . $row['dry_matter_weight_dairy_dish'] . "</td>
            </tr>";
        endforeach;

        return $table;
    }

    /**
     * @param $farmid
     * @param $rowid
     * @param $key
     * @param $value
     */
    public function Update_Herd($farmid, $rowid, $key, $value)
    {
//		$sql = "INSERT INTO herd (sample_date) VALUES ()";
        $herd_keys  = array('num_milked','average_liters_per_cow_per_day','average_liters_per_milked_cow_per_day','num_cows','dry','calves','dry_matter_weight_dairy_dish');
        if (array_search($key,$herd_keys) === FALSE)
            return false;
        if (!is_numeric($farmid) || !is_numeric($rowid))
            return false;
        $sql = "UPDATE data_entry_herd set ".$key."='".mysql_real_escape_string($value)."' WHERE farm_id='".$farmid."' AND id='".$rowid."'";
//        echo $sql;
//        exit;
        $result = $this->con->query($sql);
        if (!$result) {

            return false;
        }
        return true;
    }


    function Get_Food($FarmId,$StartDate,$FinishDate)
    {
        // Average Chart
        $sql = "SELECT
					id,
                    sample_date,
                    food_milk_kg,
                    food_calf_kg,
                    food_dry_kg,
                    food_total_kg,
                    weight_dairy_food_kg,
                    weight_dairy_female_food_kg,
                    weight_calf_food_kg,
                    weight_dry_food_kg,
                    other_food,
                    price_calf_food,
                    price_dry_food,
                    price_dairy_food,
                    price_female_dairy_food,
                    weight_food_kg
                FROM
                   data_entry_food
                WHERE
                   farm_id = " . $FarmId . "
                   AND sample_date >= '" . $StartDate . "'
                   AND sample_date <= '" . $FinishDate . "'
                ORDER by
                   sample_date desc;";

        $result = $this->con->query($sql);
        if (!$result):
            print_r($this->con->error());
            exit;
        endif;
        $result = $result->fetchAll();
        $table = "";
        foreach ($result as $row):
            $table .= "<tr data-indexid=\"".$row['id']."\">
            <td data-tdid=\"sample_date\">" . $row['sample_date'] . "</td>
            <td data-tdid=\"num_servings_transfer\">" . $row['num_servings_transfer'] . "</td>
			<td data-tdid=\"food_milk_kg\">" . $row['food_milk_kg'] . "</td>
            <td data-tdid=\"food_calf_kg\">" . $row['food_calf_kg'] . "</td>
            <td data-tdid=\"food_dry_kg\">" . $row['food_dry_kg'] . "</td>
            <td data-tdid=\"food_total_kg\">" . $row['food_total_kg'] . "</td>
            <td data-tdid=\"weight_dairy_food_kg\">" . $row['weight_dairy_food_kg'] . "</td>
            <td data-tdid=\"weight_dairy_female_food_kg\">" . $row['weight_dairy_female_food_kg'] . "</td>
            <td data-tdid=\"weight_calf_food_kg\">" . $row['weight_calf_food_kg'] . "</td>
            <td data-tdid=\"weight_dry_food_kg\">" . $row['weight_dry_food_kg'] . "</td>
            <td data-tdid=\"other_food\">" . $row['other_food'] . "</td>
            <td data-tdid=\"price_calf_food\">" . $row['price_calf_food'] . "</td>
            <td data-tdid=\"price_dry_food\">" . $row['price_dry_food'] . "</td>
            <td data-tdid=\"price_dairy_food\">" . $row['price_dairy_food'] . "</td>
            <td data-tdid=\"price_female_dairy_food\">" . $row['price_female_dairy_food'] . "</td>
            <td data-tdid=\"weight_food_kg\">" . $row['weight_food_kg'] . "</td>
            </tr>";
        endforeach;

        return $table;
    }
    /**
     * @param $farmid
     * @param $rowid
     * @param $key
     * @param $value
     */
    public function Update_Food($farmid, $rowid, $key, $value)
    {
//		$sql = "INSERT INTO herd (sample_date) VALUES ()";
        $herd_keys  = array('food_milk_kg','food_calf_kg','food_dry_kg','food_total_kg','weight_dairy_food_kg','weight_dairy_female_food_kg','weight_calf_food_kg','weight_dry_food_kg','other_food','price_calf_food','price_dry_food','price_dairy_food','price_female_dairy_food','weight_food_kg');
        if (array_search($key,$herd_keys) === FALSE)
            return false;
        if (!is_numeric($farmid) || !is_numeric($rowid))
            return false;
        $sql = "UPDATE data_entry_food set ".$key."='".mysql_real_escape_string($value)."' WHERE farm_id='".$farmid."' AND id='".$rowid."'";
//        echo $sql;
//        exit;
        $result = $this->con->query($sql);
        if (!$result) {

            return false;
        }
        return true;
    }


    function Get_Milk($FarmId,$StartDate,$FinishDate)
    {
        // Average Chart
        $sql = "SELECT
					id,
                    sample_date,
                    milk_marketing_planning_liters,
                    amount_of_milk_marketed_liters,
                    protein_percentage,
                    fat_percentage,
                    milk_income,
                    fee_income_less_milk_dairy_board,
                    target_price,
                    liter_of_milk_in_practice,
                    quota_percentage,
                    milk_quota
                FROM
                   data_entry_milk
                WHERE
                   farm_id = " . $FarmId . "
                   AND sample_date >= '" . $StartDate . "'
                   AND sample_date <= '" . $FinishDate . "'
                ORDER by
                   sample_date desc;";

        $result = $this->con->query($sql);
        if (!$result):
            print_r($this->con->error());
            exit;
        endif;
        $result = $result->fetchAll();
        $table = "";
        foreach ($result as $row):
            $table .= "<tr data-indexid=\"".$row['id']."\">
            <td data-tdid=\"sample_date\">" . $row['sample_date'] . "</td>
            <td data-tdid=\"milk_marketing_planning_liters\">" . $row['milk_marketing_planning_liters'] . "</td>
            <td data-tdid=\"amount_of_milk_marketed_liters\">" . $row['amount_of_milk_marketed_liters'] . "</td>
            <td data-tdid=\"protein_percentage\">" . $row['protein_percentage'] . "</td>
            <td data-tdid=\"fat_percentage\">" . $row['fat_percentage'] . "</td>
            <td data-tdid=\"milk_income\">" . $row['milk_income'] . "</td>
            <td data-tdid=\"fee_income_less_milk_dairy_board\">" . $row['fee_income_less_milk_dairy_board'] . "</td>
            <td data-tdid=\"target_price\">" . $row['target_price'] . "</td>
            <td data-tdid=\"liter_of_milk_in_practice\">" . $row['liter_of_milk_in_practice'] . "</td>
            <td data-tdid=\"quota_percentage\">" . $row['quota_percentage'] . "</td>
            <td data-tdid=\"milk_quota\">" . $row['milk_quota'] . "</td>
            </tr>";
        endforeach;

        return $table;
    }
    /**
     * @param $farmid
     * @param $rowid
     * @param $key
     * @param $value
     */
    public function Update_Milk($farmid, $rowid, $key, $value)
    {
//		$sql = "INSERT INTO data_entry_milk (sample_date) VALUES ()";
        $herd_keys  = array('milk_marketing_planning_liters','amount_of_milk_marketed_liters','protein_percentage','fat_percentage','milk_income','fee_income_less_milk_dairy_board','target_price','liter_of_milk_in_practice','quota_percentage','milk_quota');
        if (array_search($key,$herd_keys) === FALSE)
            return false;
        if (!is_numeric($farmid) || !is_numeric($rowid))
            return false;
        $sql = "UPDATE data_entry_milk set ".$key."='".mysql_real_escape_string($value)."' WHERE farm_id='".$farmid."' AND id='".$rowid."'";
//        echo $sql;
//        exit;
        $result = $this->con->query($sql);
        if (!$result) {

            return false;
        }
        return true;
    }
    // ///////////////////////////////////////// FARM /////////////////////////////////////////

    function Farm_Get_Details($farm_id)
    {
        if (!is_numeric($farm_id)):
            return false;
        endif;
        $sql = "SELECT * FROM farm  WHERE id=$farm_id";
//        echo $sql;
//        exit;
        $result = $this->con->query($sql);
        if (!$result) {
            echo $this->con->error();
            exit;
        }

        $row = $result->fetchObject();
//        echo '<pre>' . print_r(get_defined_vars(), true) . '</pre>';
//            exit;


//            echo mysql_error();
//            exit;
//        return array($row['username'],$row['first_name'],$row['last_name'],$row['language'],$row['image']);
        return $row;
    }

    public function Farm_register($farm_name ,$alias ,$address,$start_date, $qlikview_code , $country , $diary_milk_username , $diary_milk_password , $diary_milk_type , $diary_milk_enable , $sensor_username , $sensor_password , $sensor_type , $sensor_enable , $feed_center_username , $feed_center_password , $feed_center_type , $feed_center_enable , $bacarit_username , $bacarit_password , $bacarit_type , $bacarit_enable , $milk_production_username , $milk_production_password , $milk_production_type , $milk_production_enable , $data_entry_enable, $analytics_enable )
    {
        $sql = "INSERT INTO `cowshed`.`farm` (`id`, `external_code`, `name`, `country`," .
            " `diary_milk_username`, `diary_milk_password`, `diary_milk_type`, `diary_milk_enable`, `sensor_username`," .
            " `sensor_password`, `sensor_type`, `sensor_enable`, `feed_center_username`, `feed_center_password`," .
            " `feed_center_type`, `feed_center_enable`, `bacarit_username`, `bacarit_password`, `bacarit_type`," .
            " `bacarit_enable`, `milk_production_username`, `milk_production_password`, `milk_production_type`, `milk_production_enable`," .
            " `data_entry_enable`, `alias`, `address`, `Start_date`, `analytics_enable`) "
            . " VALUES " .
            "(NULL, '" . $qlikview_code . "', '" . $farm_name . "', '" . $country . "'," .
            " '" . $diary_milk_username . "', '" . $diary_milk_password . "', '" . $diary_milk_type . "', '" . $diary_milk_enable . "', '" . $sensor_username . "'," .
            " '" . $sensor_password . "', '" . $sensor_type . "', '" . $sensor_enable . "', '" . $feed_center_username . "', '" . $feed_center_password . "'," .
            " '" . $feed_center_type . "', '" . $feed_center_enable . "', '" . $bacarit_username . "', '" . $bacarit_password . "', '" . $bacarit_type . "'," .
            " '" . $bacarit_enable . "', '" . $milk_production_username . "', '" . $milk_production_password . "', '" . $milk_production_type . "', '" . $milk_production_enable . "'," .
            " '" . $data_entry_enable . "', '" . $alias  . "', '" .  $address  . "', '" . $start_date . "', '" . $analytics_enable . "');";

//        echo $sql;exit;
        $result = $this->con->query($sql);
        if (!$result) {

            return false;
        }
        else return true;
    }

    public function Farm_Update_Details($farm_id,$alias ,$address,$start_date,$farm_name , $qlikview_code , $country , $diary_milk_username , $diary_milk_password , $diary_milk_type , $diary_milk_enable , $sensor_username , $sensor_password , $sensor_type , $sensor_enable , $feed_center_username , $feed_center_password , $feed_center_type , $feed_center_enable , $bacarit_username , $bacarit_password , $bacarit_type , $bacarit_enable , $milk_production_username , $milk_production_password , $milk_production_type , $milk_production_enable , $data_entry_enable, $analytics_enable )
    {
        if ($farm_id==0):
            return false;
        endif;
        $sql = "UPDATE farm SET ";
        if ($farm_name!=''):
            $sql_params []= "`name`='".$farm_name."'";
        endif;

        $sql_params []= "`alias`='".$alias."'";
        $sql_params []= "`address`='".$address."'";
        $sql_params []= "`start_date`='".$start_date."'";

        $sql_params []= "`external_code`='".$qlikview_code."'";
        $sql_params []= "`country`='".$country."'";

        $sql_params []= "`diary_milk_username`='".$diary_milk_username."'";
        $sql_params []= "`diary_milk_password`='".$diary_milk_password."'";
        $sql_params []= "`diary_milk_type`='".$diary_milk_type."'";
        $sql_params []= "`diary_milk_enable`='".$diary_milk_enable."'";

        $sql_params []= "`sensor_username`='".$sensor_username."'";
        $sql_params []= "`sensor_password`='".$sensor_password."'";
        $sql_params []= "`sensor_type`='".$sensor_type."'";
        $sql_params []= "`sensor_enable`='".$sensor_enable."'";

        $sql_params []= "`feed_center_username`='".$feed_center_username."'";
        $sql_params []= "`feed_center_password`='".$feed_center_password."'";
        $sql_params []= "`feed_center_type`='".$feed_center_type."'";
        $sql_params []= "`feed_center_enable`='".$feed_center_enable."'";

        $sql_params []= "`bacarit_username`='".$bacarit_username."'";
        $sql_params []= "`bacarit_password`='".$bacarit_password."'";
        $sql_params []= "`bacarit_type`='".$bacarit_type."'";
        $sql_params []= "`bacarit_enable`='".$bacarit_enable."'";

        $sql_params []= "`milk_production_username`='".$milk_production_username."'";
        $sql_params []= "`milk_production_password`='".$milk_production_password."'";
        $sql_params []= "`milk_production_type`='".$milk_production_type."'";
        $sql_params []= "`milk_production_enable`='".$milk_production_enable."'";

        $sql_params []= "`data_entry_enable`='".$data_entry_enable."'";
        $sql_params []= "`analytics_enable`='".$analytics_enable."'";

        $sql .= implode(',',$sql_params);
        $sql .= ' WHERE `id`='.$farm_id;
//echo $sql;exit;
        $result = $this->con->query($sql);
        if (!$result) {
//			echo $this->con->error();exit;
            return false;
        }
        return true;
    }

	public function GetUserFarm($userid = '')
	{
		$sql = " SELECT f.id, f.name, user_farm.farm_id"
			. " FROM farm f"
			. " LEFT JOIN user_farm ON f.id=user_farm.farm_id  AND user_farm.user_id=".$userid;
//			." ORDER BY farm.id asc;";
//echo $sql;exit;
		$result = $this->con->query($sql)->fetchAll();

		return $result;
	}


    public function GetMyFarm($userid = '')
    {
        $sql = " SELECT f.id, f.name, user_farm.farm_id"
            . " FROM farm f"
            . " JOIN user_farm ON f.id=user_farm.farm_id  AND user_farm.user_id=".$userid;
//			." ORDER BY farm.id asc;";
//echo $sql;exit;
        $result = $this->con->query($sql)->fetchAll();

        return $result;
    }

	private function deleteUserFarms($userid = '')
	{
		$sql = 'DELETE FROM user_farm WHERE user_id='.$userid;
		$result = $this->con->query($sql);
		return $result;
	}
	public function deleteUser($userid = '')
	{
		// Deletinng the user image
		unlink(DIRECTORY.'/img/profile/' . $userid . '.jpg');
		// Delete user farms
		if (!$this->deleteUserFarms($userid)):
			return false;
		endif;
		$sql = 'UPDATE user SET `hide`=1 WHERE id='.$userid;
		$sql = 'DELETE FROM user WHERE id='.$userid;
		$result = $this->con->query($sql);
		return $result;
	}

	public function setUserFarms($userid = '', $farms)
	{
		if (!$this->deleteUserFarms($userid)):
			return false;
		endif;
		if (!empty($farms)):
			$sql = 'INSERT INTO user_farm (`farm_id`, `user_id`) VALUES ';
			$sql .= '(\''.implode('\',\''.$userid.'\'), (\'',$farms).'\',\''.$userid.'\')';
	//		echo $sql;exit;
			$result = $this->con->query($sql);
			return $result;
		else:
			return true;
		endif;
	}

    function GetUsers()
    {
        $sql = " SELECT u.username, u.first_name, u.last_name,	u.id ,u.bi_user, u.bi_pass, m.name "
            . " FROM user as u , mapping_role as m "
            . " WHERE m.id = u.role "
            ." ORDER BY u.id asc;";
//echo $sql;exit;
        $result = $this->con->query($sql)->fetchAll();

        $table = "";
        foreach ($result as $row):
            $table .= "<tr>
            <td>" . $row['username'] . "</td>
            <td>" . $row['first_name'] . "</td>
            <td>" . $row['last_name'] . "</td>
            <td>" . $row['name'] . "</td>
            <td>" . $row['bi_user'] . "</td>
            <td>" . $row['bi_pass'] . "</td>
            <td>" . '<a href='. APP_URL.'/Setting/user_farm.php?userid='. $row['id'] . ' class="fa fa-gears "></a>'. "</td>
            <td>" . '<a href='. APP_URL.'/Setting/user.php?userid='. $row['id'] . ' class="fa fa-pencil "></a>'. "</td>
            <td>" . '<a href="#" data-userid="'.$row['id'].'" class="smart-mod-eg1 fa fa-trash-o fa-md"></a>'. "</td>

            </tr>";
        endforeach;

        return $table;
    }
/*
    function User_Del_User_Id($user_id)
    {
        $sql = "DELETE from user_farm  WHERE user_id = '" . $user_id . "'";
        $result = $this->con->query($sql);

        $sql = "DELETE from user  WHERE user_id = '" . $user_id . "'";
        $result = $this->con->query($sql);

        if (!$result) {
            echo $this->con->error();
            exit;
        }
        return;
    }*/


    function GetFarms()
    {
        $sql = " SELECT farm.id, farm.name as Farm_name, farm.external_code, farm.alias, farm.address, farm.start_date, country.name as country"
            . " FROM farm "
            . "LEFT JOIN country  ON farm.country = country.id"
            ." ORDER BY id asc;";
//echo $sql;exit;
        $result = $this->con->query($sql)->fetchAll();

        $table = "";
        foreach ($result as $row):
            $table .= "<tr>
            <td>" . $row['Farm_name'] . "</td>
            <td>" . $row['alias'] . "</td>
            <td>" . $row['address'] . "</td>
            <td>" . $row['external_code'] . "</td>
            <td>" . $row['start_date'] . "</td>
            <td>" . $row['country'] . "</td>
            <td>" . '<a href='. APP_URL.'/Setting/farm.php?farm_id='. $row['id'] . ' class="fa fa-pencil "></a>'. "</td>
            <td>" . '<a href="#" data-farmid="'.$row['id'].'" class="smart-mod-eg1 fa fa-trash-o fa-md"></a>'. "</td>
            </tr>";
        endforeach;

        return $table;
    }

	private function deleteFarmUsers($farmid = '')
	{
		$sql = 'DELETE FROM user_farm WHERE farm_id='.$farmid;
		$result = $this->con->query($sql);
		return $result;
	}
	public function deleteFarm($farmid = '')
	{
		// Delete user farms
		if (!$this->deleteFarmUsers($farmid)):
			return false;
		endif;
		$sql = 'DELETE FROM farm WHERE id='.$farmid;
//        echo $sql;exit;
		$result = $this->con->query($sql);
		return $result;
	}

//    ====================================================================================
//    ====================================================================================
//    ====================================================================================

    function Group_Get_Details($group_id)
    {
        if (!is_numeric($group_id)):
            return false;
        endif;
        $sql = "SELECT * FROM  smaxtec_animal_group  WHERE id=$group_id";
//        echo $sql;
//        exit;
        $result = $this->con->query($sql);
        if (!$result) {
            echo $this->con->error();
            exit;
        }
        $row = $result->fetchObject();
        return $row;
    }

    function Group_Get_Details_By_Name($name)
    {
        $sql = "SELECT id, name FROM  smaxtec_animal_group  WHERE name = '" . $name . "'";
//        echo $sql;
//        exit;
        $result = $this->con->query($sql);
        if (!$result) {
            echo $this->con->error();
            exit;
        }

        $row = $result->fetchObject();
        if (!isset($row->id)):
            return false;
        endif;
        return array($row->id,$row->name);
    }

    function Group_Register($smaxtec_user_id,$name)
    {
        $sql = "INSERT INTO smaxtec_animal_group (name,smaxtec_user_id) "
            ."VALUES('".$name."','".$smaxtec_user_id."')";
//        echo $sql;exit;
        $result = $this->con->query($sql);
        if (!$result) {

            return false;
        }
        else return true;
    }

    function Group_Get_Group_Id($group_id)
    {
        $sql = "SELECT id FROM  smaxtec_animal_group  WHERE id = '" . $group_id . "'";
        echo $sql;
        exit;

        $result = $this->con->query($sql);
        if (!$result) {
            echo $this->con->error();
            exit;
        }

        $row = $result->fetchObject();

        return ($row->id);
    }

    function Group_Update($group_id, $name)
    {
        if ($group_id==0):
            return false;
        endif;
        $sql = "UPDATE smaxtec_animal_group set ";

        $sql_params []= "`name`='".$name."'";
        $sql .= implode(',',$sql_params);
        $sql .= ' WHERE `id`='.$group_id;
//echo $sql;exit;
        $result = $this->con->query($sql);
        if (!$result) {
//			echo $this->con->error();exit;
            return false;
        }
        return true;
    }


    function GetGroupTable($smaxtec_user_id)
    {
        $sql = "SELECT id, name "
            . "FROM smaxtec_animal_group "
            . "WHERE smaxtec_user_id = " . $smaxtec_user_id
            ." ORDER BY id asc;";
//echo $sql;exit;
        $result = $this->con->query($sql)->fetchAll();

        $table = "";
        foreach ($result as $row):
            $table .= "<tr>
            <td>" . $row['name'] . "</td>
            <td>" . '<a href='. APP_URL.'/data_entry/smaxtec_group/group_cow.php?group_id='. $row['id'] . ' class="fa fa-gears "></a>'. "</td>
            <td>" . '<a href='. APP_URL.'/data_entry/smaxtec_group/group_time_list.php?group_id='. $row['id'] . ' class="fa fa-clock-o "></a>'. "</td>
            <td>" . '<a href='. APP_URL.'/data_entry/smaxtec_group/group.php?group_id='. $row['id'] . ' class="fa fa-pencil "></a>'. "</td>
            <td>" . '<a href="#" data-groupid="'.$row['id'].'" class="smart-mod-eg1 fa fa-trash-o fa-md"></a>'. "</td>
            </tr>";
        endforeach;

        return $table;
    }

    public function DeleteGroup($groupid = '',$smaxtec_user_id)
    {

        if (!$this->ResetFarmCows($smaxtec_user_id,$groupid)):
            return false;
        endif;

        $sql = 'DELETE FROM smaxtec_animal_group WHERE id='.$groupid;
//        echo $sql;exit;
        $result = $this->con->query($sql);
        return $result;
    }


    public function GetGroupList($smaxtec_user_id)
    {
        $sql = "SELECT id, name "
            . "FROM smaxtec_animal_group "
            . "WHERE smaxtec_user_id = " . $smaxtec_user_id
            ." ORDER BY id asc;";
            //echo $sql;exit;
        $result = $this->con->query($sql)->fetchAll();

        return $result;
    }

    function Group_Time_Get_Details($smaxtec_user_id,$id)
    {
        if (!is_numeric($id)):
            return false;
        endif;
        $sql = "SELECT * FROM  smaxtec_animal_group_time  WHERE id=" . $id." AND smaxtec_user_id = " . $smaxtec_user_id;
//        echo $sql;
//        exit;
        $result = $this->con->query($sql);
        if (!$result) {
            echo $this->con->error();
            exit;
        }
        $row = $result->fetchObject();
        return $row;
    }

    function Group_Time_Get_Details_By_Name($name,$smaxtec_user_id,$group_id)
    {
        $sql = "SELECT * FROM  smaxtec_animal_group_time  WHERE name='" . $name."' AND smaxtec_user_id = " . $smaxtec_user_id
            ." AND group_id = " . $group_id;
//        echo $sql;
//        exit;
        $result = $this->con->query($sql);
        if (!$result) {
            echo $this->con->error();
            exit;
        }

        $row = $result->fetchObject();
        if (!isset($row->id)):
            return false;
        endif;
        return array($row->id,$row->name);
    }

    function Group_Time_Register($smaxtec_user_id,$group_id,$name,$start_time,$end_time,$color)
    {
        $sql = "INSERT INTO smaxtec_animal_group_time (smaxtec_user_id,group_id,name,start_time,end_time,color) "
            ."VALUES('".
            $smaxtec_user_id."','".
            $group_id."','".
            $name."','".
            $start_time."','".
            $end_time."','".
            $color
            ."')";
//        echo $sql;exit;
        $result = $this->con->query($sql);
        if (!$result) {

            return false;
        }
        else return true;
    }

    function Group_Time_Update($id,$group_id,$name,$start_time,$end_time,$color)
    {
        if ($id==0):
            return false;
        endif;
        $sql = "UPDATE smaxtec_animal_group_time set ";

        $sql_params []= "`name`='".$name."'";
        $sql_params []= "`group_id`='".$group_id."'";
        $sql_params []= "`start_time`='".$start_time."'";
        $sql_params []= "`end_time`='".$end_time."'";
        $sql_params []= "`color`='".$color."'";

        $sql .= implode(',',$sql_params);
        $sql .= ' WHERE `id`='.$id;
        //echo $sql;exit;
        $result = $this->con->query($sql);
        if (!$result) {
//			echo $this->con->error();exit;
            return false;
        }
        return true;
    }

    function Group_Time_Delete($smaxtec_user_id = '',$group_id)
    {
        $sql = 'DELETE FROM smaxtec_animal_group_time ';
        $sql .= ' WHERE smaxtec_user_id='.$smaxtec_user_id;
        $sql .= ' AND group_id='.$group_id;
        $result = $this->con->query($sql);
        return $result;
    }


    function Group_Time_Delete_By_Id($smaxtec_user_id = '',$id)
    {
        $sql = 'DELETE FROM smaxtec_animal_group_time ';
        $sql .= ' WHERE smaxtec_user_id='.$smaxtec_user_id;
        $sql .= ' AND id='.$id;
        $result = $this->con->query($sql);
        return $result;
    }


    function Group_Time_Get_Table($smaxtec_user_id,$group_id)
    {
        $sql = "SELECT st.id ,sg.name as group_name , st.name as time_name , st.start_time, st.end_time"
            . " FROM smaxtec_animal_group_time as st ,"
            . "smaxtec_animal_group as sg "
            . "WHERE st.smaxtec_user_id = " . $smaxtec_user_id
            . " AND group_id = " . $group_id
            . " AND sg.id = st.group_id"
            ." ORDER BY id asc;";
//        echo $sql;exit;
        $result = $this->con->query($sql)->fetchAll();

        $table = "";
        foreach ($result as $row):
            $table .= "<tr>
            <td>" . $row['group_name'] . "</td>
            <td>" . $row['time_name'] . "</td>
            <td>" . $row['start_time'] . "</td>
            <td>" . $row['end_time'] . "</td>
            <td>" . '<a href='. APP_URL.'/data_entry/smaxtec_group/group_time.php?group_time_id='. $row['id'] . ' class="fa fa-pencil "></a>'. "</td>
            <td>" . '<a href="#" data-groupid="'.$row['id'].'" class="smart-mod-eg1 fa fa-trash-o fa-md"></a>'. "</td>
            </tr>";
        endforeach;

        return $table;
    }

    public function GetFarmCows($smaxtec_user_id= '',$group_id)
    {
        $sql = " SELECT id, name, group_id"
            . " FROM smaxtec_animal_info "
            . " WHERE smaxtec_user_id=".$smaxtec_user_id
            . " AND ( group_id =".$group_id." OR group_id IS NULL )";
        //    echo $sql;exit;
        $result = $this->con->query($sql)->fetchAll();

        return $result;
    }


    public function ResetFarmCows($smaxtec_user_id = '',$group_id)
    {
        $sql = "UPDATE smaxtec_animal_info SET group_id=NULL";
        $sql .= ' WHERE smaxtec_user_id='.$smaxtec_user_id;
        $sql .= ' AND group_id='.$group_id;
        $result = $this->con->query($sql);
        return $result;
    }


    public function SetFarmCows($smaxtec_user_id = '',$group_id, $cows)
    {
        if (!$this->ResetFarmCows($smaxtec_user_id,$group_id)):
            return false;
        endif;

        if (!$this->Group_Time_Delete($smaxtec_user_id,$group_id)):
            return false;
        endif;

        if (!empty($cows)):
            $sql = "UPDATE smaxtec_animal_info SET group_id=".$group_id;
            $sql .= ' WHERE smaxtec_user_id='.$smaxtec_user_id;
            $sql .= ' AND id in (';
            for ($x = 0; $x < sizeof($cows); $x++) {
                if ($x == (sizeof($cows)-1) ){
                    $sql_params []= $cows[$x].")";
                }else{
                    $sql_params []= $cows[$x].",";
                }
            }
            $sql .= implode('',$sql_params);
//    		echo $sql;exit;
            $result = $this->con->query($sql);
            return $result;
        else:
            return true;
        endif;
    }


    // //////////////////////  Tnuva  //////////////////////
    function Get_Tnuva_Manufacturer_Invoices($TnuvaUserId,$StartDate,$EndDate)
    {
        // Average Chart
        $sql = "SELECT
                   ProductionDate,
                   PayDate,
                   VatDate,
                   AccountType,
                   InvoiceNum,
                   FromDate,
                   ToDate,
                   TotalPreVat,
                   Vat
                FROM
                   tnuvamonthbill
                WHERE
                   UserId = '" . $TnuvaUserId . "'
                   AND ProductionDate >= '" . $StartDate . "'
                   AND ProductionDate <= '" . $EndDate . "'
                ORDER by
                   ProductionDate desc;";

        $result = $this->con->query($sql)->fetchAll();

        $table = "";
        $id = 1;
        foreach ($result as $row):
            $table .= "<tr>
        <td>" . $id . "</td>
        <td>" . $row['ProductionDate'] . "</td>
        <td>" . $row['PayDate'] . "</td>
        <td>" . $row['VatDate'] . "</td>
        <td>" . $row['AccountType'] . "</td>
        <td>" . $row['InvoiceNum'] . "</td>
        <td>" . $row['FromDate'] . "</td>
        <td>" . $row['ToDate'] . "</td>
        <td>" . number_format($row['TotalPreVat']) . "</td>
        <td>" . number_format($row['Vat']) . "</td>
        </tr>
        ";
            $id++;
        endforeach;

        return $table;


    }

    
    function Get_Tnuva_Manufacturer_Profile($TnuvaUserId,$StartDate,$EndDate)
    {
        // Average Chart
        $sql = "SELECT
                    SampeDate,
                    Dairy,
                    Tanker,
                    DeliveryNumber ,
                    ReportedMilk,
                    ApproveMilk,
                    InvalidMilk,
                    FatPercentage,
                    ProteinPercentage,
                    LactosePercentage,
                    Acidity,
                    Temperature,
                    WaterPercent,
                    Ma,
                    Ta,
                    Mr,
                    Rh,
                    Gz
                FROM
                   TnuvaManufacturerProfile
                WHERE
                   UserId = " . $TnuvaUserId . "
                   AND DATE_FORMAT(SampeDate,'%Y-%m-%d') >= '" . $StartDate . "'
                   AND DATE_FORMAT(SampeDate,'%Y-%m-%d') <= '" . $EndDate . "'
                ORDER by
                   SampeDate desc;";
//        echo $sql;exit;
        $result = $this->con->query($sql)->fetchAll();

        $table = "";
        $id = 1;
        foreach ($result as $row):
            $table .= "<tr>
            <td>" . $id . "</td>
            <td>" . $row['SampeDate'] . "</td>
            <td>" . $row['Dairy'] . "</td>
            <td>" . $row['Tanker'] . "</td>
            <td>" . $row['DeliveryNumber'] . "</td>
            <td>" . $row['ReportedMilk'] . "</td>
            <td>" . $row['ApproveMilk'] . "</td>
            <td>" . $row['InvalidMilk'] . "</td>
            <td>" . $row['FatPercentage'] . "</td>
            <td>" . $row['ProteinPercentage'] . "</td>
            <td>" . $row['LactosePercentage'] . "</td>
            <td>" . $row['Acidity'] . "</td>
            <td>" . $row['Temperature'] . "</td>
            <td>" . $row['WaterPercent'] . "</td>
            <td>" . $row['Ma'] . "</td>
            <td>" . $row['Ta'] . "</td>
            <td>" . $row['Mr'] . "</td>
            <td>" . $row['Rh'] . "</td>
            <td>" . $row['Gz'] . "</td>
            </tr>";
            $id++;
        endforeach;

        return $table;
    }

    function Get_Tnuva_Milk_Quality_Bacteria($TnuvaUserId,$StartDate,$EndDate)
    {
        // Average Chart
        $sql = "SELECT
                    Date,
                    Dairy,
                    Count,
                    Average,
                    M
                FROM
                   tnuvamilkqualitybacteria
                WHERE
                   UserId = " . $TnuvaUserId . "
                   AND DATE_FORMAT(Date,'%Y-%m-%d') >= '" . $StartDate . "'
                   AND DATE_FORMAT(Date,'%Y-%m-%d') <= '" . $EndDate . "'
                ORDER by
                   Date desc;";
        //echo $sql;exit;
        $result = $this->con->query($sql)->fetchAll();

        $table = "";
        $id = 1;
        foreach ($result as $row):
            $table .= "<tr>
            <td>" . $id . "</td>
            <td>" . $row['Date'] . "</td>
            <td>" . $row['Dairy'] . "</td>
            <td>" . $row['Count'] . "</td>
            <td>" . $row['Average'] . "</td>
            <td>" . $row['M'] . "</td>
            </tr>";
            $id++;
        endforeach;

        return $table;
    }

    function Get_Tnuva_Milk_Quality_Somatic($TnuvaUserId,$StartDate,$EndDate)
    {
        // Average Chart
        $sql = "SELECT
                    Date,
                    Dairy,
                    Count,
                    Average
                FROM
                   tnuvamilkqualitysomatic
                WHERE
                   UserId = " . $TnuvaUserId . "
                   AND DATE_FORMAT(Date,'%Y-%m-%d') >= '" . $StartDate . "'
                   AND DATE_FORMAT(Date,'%Y-%m-%d') <= '" . $EndDate . "'
                ORDER by
                   Date desc;";
        //echo $sql;exit;
        $result = $this->con->query($sql)->fetchAll();

        $table = "";
        $id = 1;
        foreach ($result as $row):
            $table .= "<tr>
            <td>" . $id . "</td>
            <td>" . $row['Date'] . "</td>
            <td>" . $row['Dairy'] . "</td>
            <td>" . $row['Count'] . "</td>
            <td>" . $row['Average'] . "</td>
            </tr>";
            $id++;
        endforeach;

        return $table;
    }


    function Get_Tnuva_Marketing_Vs_Cover($TnuvaUserId,$Year)
    {
        // Average Chart
        $sql = "SELECT
                    Month,
                    MonthlyCover,
                    CumulativeCover,
                    InnovativeMarketing,
                    Cumulative,
                    Supply,
                    A_Quantity,
                    A_Sum,
                    B_Quantity,
                    B_Sum,
                    Total
                FROM
                   tnuvamarketing_vs_coverpage
                WHERE
                   UserId = " . $TnuvaUserId . "
                   AND Month like '" . $Year . "-%'
                ORDER by
                   Month desc;";

        $result = $this->con->query($sql)->fetchAll();

        $table = "";
        $id = 1;
        foreach ($result as $row):
            $table .= "<tr>
            <td>" . $id . "</td>
            <td>" . $row['Month'] . "</td>
            <td>" . $row['MonthlyCover'] . "</td>
            <td>" . $row['CumulativeCover'] . "</td>
            <td>" . $row['InnovativeMarketing'] . "</td>
            <td>" . $row['Cumulative'] . "</td>
            <td>" . $row['Supply'] . "</td>
            <td>" . $row['A_Quantity'] . "</td>
            <td>" . $row['A_Sum'] . "</td>
            <td>" . $row['B_Quantity'] . "</td>
            <td>" . $row['B_Sum'] . "</td>
            <td>" . $row['Total'] . "</td>
            </tr>";
            $id++;
        endforeach;

        return $table;
    }

    function Get_Tnuva_Consumer_Financial_Concentration($TnuvaUserId,$Year)
    {
        // Average Chart
        $sql = "SELECT
                    CONCAT(SUBSTRING_INDEX(Month, '/',-1),'-',LPAD(SUBSTRING_INDEX(Month, '/',1),2,'0')) as Month,
                    TargetPrice,
                    MilkAmount,
                    MilkSum,
                    FatSum,
                    ProteinSum,
                    BacteriaSum,
                    SomaticSum,
                    CancellationCouncil,
                    AdditionalCharges,
                    TotalCorrections,
                    LiterCost,
                    TotalPay
                FROM
                   tnuvaconsumerfinancialconcentration
                WHERE
                   UserId = " . $TnuvaUserId . "
                   AND Month like '%/" . $Year . "'
                ORDER by
                   Id desc;";
        // echo $sql;exit;
        $result = $this->con->query($sql)->fetchAll();

        $table = "";
        $id = 1;
        foreach ($result as $row):
            $table .= "<tr>
            <td>" . $id . "</td>
            <td>" . $row['Month'] . "</td>
            <td>" . $row['TargetPrice'] . "</td>
            <td>" . $row['MilkAmount'] . "</td>
            <td>" . $row['MilkSum'] . "</td>
            <td>" . $row['FatSum'] . "</td>
            <td>" . $row['ProteinSum'] . "</td>
            <td>" . $row['BacteriaSum'] . "</td>
            <td>" . $row['SomaticSum'] . "</td>
            <td>" . $row['CancellationCouncil'] . "</td>
            <td>" . $row['AdditionalCharges'] . "</td>
            <td>" . $row['TotalCorrections'] . "</td>
            <td>" . $row['LiterCost'] . "</td>
            <td>" . $row['TotalPay'] . "</td>
            </tr>";
            $id++;
        endforeach;

        return $table;
    }



}
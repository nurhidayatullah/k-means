<?php
/*	author Nur Hidayatullah (nur_hidayat_45@yahoo.com)
 *	date 12-05-2015
 */ 
class Kmeans{
	private $j = 0;
	private $perubahan = 0;
	function set_j($j_baru){
		$this->perubahan = round(abs($j_baru - $this->j),4);
		$this->j = $j_baru;
	}
	function get_perubahan(){
		return $this->perubahan;
	}
	function get_centroid($data,$k){
		$centroid = array();
		$jml = array();
		for($a = 0;$a < $k;$a++){
			$centroid[$a] = array(0,0);
			$jml[$a] = 0;
		}
		for($x=0;$x < count($data);$x++){
			$cluster = $data[$x][count($data[$x])-1];
			$jml[$cluster-1] = $jml[$cluster-1]+1;
			for($y = 0;$y < (count($data[$x])-1);$y++){
				$centroid[$cluster-1][$y] =  $centroid[$cluster-1][$y]+($data[$x][$y]);
			}
		}
		for($x=0;$x<$k;$x++){
			for($y=0;$y<count($centroid[$x]);$y++){
				$centroid[$x][$y] = round($centroid[$x][$y]/$jml[$x],4);
			}
		}
	//	echo "centroid : ";print_r($centroid);echo "</br>";
		return $centroid;
	}

	function get_distance($data,$centroid){
		for($x=0;$x<count($data);$x++){
			$cluster = $data[$x][count($data[$x])-1];
			$jarak[$x] = 0;
			for($y = 0;$y < (count($data[$x])-1);$y++){
				$jarak[$x] = $jarak[$x] + (($data[$x][$y]-$centroid[$cluster-1][$y])*($data[$x][$y]-$centroid[$cluster-1][$y]));
			}
			$jarak[$x] = round(sqrt($jarak[$x]),4);
		}
//		echo "jarak : ";print_r($jarak);echo "</br>";
		return $this->get_objective($jarak);
	}
	function get_objective($jarak){
		$j=0;
		for($x=0;$x<count($jarak);$x++){
			$j = $j+$jarak[$x];
		}
		return $j;
	}
	function get_cluster($data,$centroid){
		for($x=0;$x<count($data);$x++){
			for($c=0;$c<count($centroid);$c++){
				$jarak[$x][$c] = 0;
				for($y=0;$y<(count($data[$x])-1);$y++){
					$jarak[$x][$c] = $jarak[$x][$c] + (($data[$x][$y]-$centroid[$c][$y])*($data[$x][$y]-$centroid[$c][$y]));
				}
				$jarak[$x][$c] = sqrt($jarak[$x][$c]);
			}
		}
		$x = 0;
		$hasil = array();
		foreach ($jarak as $key) {
			$hasil[$x] = array_search(min($key), $key)+1;
			$x++;
		}
		return $hasil;
	}
}
$data = array(
	array(1,1,1),array(4,1,3),array(6,1,2),
	array(1,2,2),array(2,3,3),array(5,3,2),
	array(2,5,2),array(3,5,3),array(2,6,3),array(3,8,2)
	);
$t = 0.1;
$j = 0;
$kmeans = new Kmeans();
$kmeans->set_j($j);
for($a=0;$a<9;$a++){
	$centroid = $kmeans->get_centroid($data,3);
	$j_baru =  round($kmeans->get_distance($data,$centroid),4);
	$kmeans->set_j($j_baru);
	$perubahan = $kmeans->get_perubahan();
	if($perubahan>=$t){
		$cluster = $kmeans->get_cluster($data,$centroid);
		for($x=0;$x<count($data);$x++){
			$data[$x][2]= $cluster[$x];
		}
	}else{
		break;
	}
}
echo 'fungsi Objective iterasi ke '.$a.' : '.$perubahan;
echo "</br>";
echo "<pre>";
print_r($data);
echo "</pre></br>";
?>
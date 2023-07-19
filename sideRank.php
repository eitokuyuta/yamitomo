<?php
	/*------------------------------------------------------
	*	ランキングを生成するライブラリ
	*
	---------------------------------------------------------*/

			echo "
			<div id='side_bar'>
			";	
					//総合ランキングの件数の取得
					//------------------------------------
					$data = new databaseInit();
					$result = $data->db->prepare("SELECT count(*) FROM storage");
					$result->execute();
					$count = $result->fetchAll(PDO::FETCH_ASSOC);
					$count = $count[0]['count(*)'];
					if($count > 0){

						//総合ランキング
						//------------------------------------
						$count = $count > 6 ? 6 : $count;
						$result = $data->db->prepare("SELECT * FROM storage ORDER BY commentNum DESC LIMIT :count");
						$result->bindValue(':count', $count, PDO::PARAM_INT);
						$result->execute();	
						$ranking2 = $result->fetchAll(PDO::FETCH_ASSOC);
					}

					//カテゴリーの件数の取得
					//------------------------------------
					$data = new databaseInit();
					$result = $data->db->prepare("SELECT count(*) FROM storage WHERE category = :category");
					$result->bindValue(':category', $category, PDO::PARAM_STR);
					$result->execute();
					$count2 = $result->fetchAll(PDO::FETCH_ASSOC);
					$count2 = $count2[0]['count(*)'];
					if($count2 > 0){
						//カテゴリーのランキング
						//------------------------------------
						$count2 = $count2 > 6 ? 6 : $count2;
						$result = $data->db->prepare("SELECT * FROM storage WHERE category = :category ORDER BY commentNum DESC LIMIT :count2");
						$result->bindValue(':count2', $count2, PDO::PARAM_INT);
						$result->bindValue(':category', $category, PDO::PARAM_STR);
						$result->execute();	
						$ranking = $result->fetchAll(PDO::FETCH_ASSOC);
					}
				
			echo "
				<div class='ranking'>
					<!--カテゴリーランキング-->
					<h5 class='ranking_subject'><i class='bi bi-bookmark-heart-fill'></i>" . $category . "人気記事</h5>
					<div class='rank_box'>
				";
						
							function ranking_content($fields, $num){
								return "
								<form class='ranking_form' method='get' action='./storageLog.php' name='room'>
									<input type='hidden' name='chid' value={$fields['chid']}>
									<input type='hidden' name='roomname' value={$fields['roomname']}>
									<input type='hidden' name='category' value={$fields['category']}>
									<div class='ranking-ob'>
										<img class='ranking-img'src={$fields['roomimg']}>
										<div class='ranking-num'>{$num}</div>
										<div class='ranking-value'>{$fields['roomname']}</div>
										<input class='resetSubmit hidden-button' type='submit' value=''>
									</div>
								</form>
								";
							}

							if($count2>0){echo ranking_content($ranking[0], 1);}else{echo "<div class='ranking_form'>1</div>";}
							if($count2>1){echo ranking_content($ranking[1], 2);}else{echo "<div class='ranking_form'>2</div>";}
							if($count2>2){echo ranking_content($ranking[2], 3);}else{echo "<div class='ranking_form'>3</div>";}
							if($count2>3){echo ranking_content($ranking[3], 4);}else{echo "<div class='ranking_form'>4</div>";}
							if($count2>4){echo ranking_content($ranking[4], 5);}else{echo "<div class='ranking_form'>5</div>";}
							if($count2>5){echo ranking_content($ranking[5], 6);}else{echo "<div class='ranking_form'>6</div>";}
								
			echo"
					</div>
				</div>
				<div class='ranking'>
					<!--総合ランキング-->
					<h5 class='rank_chi ranking_subject'><i class='bi bi-bookmark-heart-fill'></i>総合人気記事</h5>
					<div class='rank_box'>
			";
							if($count>0){echo ranking_content($ranking2[0], 1);}else{echo "<div class='ranking_form'>1</div>";}							
							if($count>1){echo ranking_content($ranking2[1], 2);}else{echo "<div class='ranking_form'>2</div>";}
							if($count>2){echo ranking_content($ranking2[2], 3);}else{echo "<div class='ranking_form'>3</div>";}						
							if($count>3){echo ranking_content($ranking2[3], 4);}else{echo "<div class='ranking_form'>4</div>";}
							if($count>4){echo ranking_content($ranking2[4], 5);}else{echo "<div class='ranking_form'>5</div>";}
							if($count>5){echo ranking_content($ranking2[5], 6);}else{echo "<div class='ranking_form'>6</div>";}
							
						
						
		echo "		
					</div>
				</div>
		";		
					require_once('./snsView.php');	//->share.php
				
		echo "
			</div>
			";
		?>
var todouhukenn = [
	'北海道地方'
	,'東北地方'
	,'関東地方'
	,'中部地方'
	,'近畿地方'
	,'中国地方'
	,'四国地方'
	,'九州地方'
];
var hattatu = [
	'ADHD（注意欠陥・多動性障害）'
	,'ASD（自閉症スペクトラム）'
	,'自閉症'
	,'学習障害（LD）'
	,'発達障害グレーゾーン'
	,'吃音症'
	,'チック／トゥレット／汚言症'
	,'知的障害'

];
var mentalHealth = [
	'鬱病'
	,'躁うつ病'
	,'統合失調症'
	,'強迫性障害'
	,'パニック障害'
	,'社交不安障害'
	,'場面緘黙症'
	,'愛着障害/アダルトチルドレン（AC）'
	,'依存症'
	,'PTSD/トラウマ'
	,'解離性障害/解離性同一性障害'
];
var parsonalityDisorder = [
	'境界性パーソナリティ障害'
	,'自己愛性パーソナリティ障害'
	,'回避性パーソナリティ障害'
	,'演技性パーソナリティ障害'
	,'スキゾイドパーソナリティ障害'
	,'サイコパス／ソシオパス'

];
//感覚異常
var paresthesia = [
	'聴覚障害／聴覚処理障害（APD）'
	,'運動音痴／協調運動障害（DCD）'
	,'慢性疲労症候群（CFS）'
	,'過敏性腸症候群（IBS）'
	,'アトピー／アレルギー'
	,'てんかん（癲癇）'
	,'睡眠障害／ナルコレプシー'
	,'感覚過敏／聴覚過敏'
	,'認知症／若年性アルツハイマー'
	,'難病／奇病'

];
var hspGited = [
	'天才・ギフテッド'
	,'HSP（繊細すぎる人々）'
	,'共感覚（シナスタジア）'
	,'左右盲'
];
var lgbt = [
	'性同一性障害（GID）'
	,'同性愛'
	,'Xジェンダー（中性/無性）'
	,'アセクシャル/ノンセクシャル'
	,'性的嗜好/特殊性癖'
];
var sinnrigaku = [
	'精神科・心療内科'
	,'診断・テスト'
	,'IQ・知能指数'
];
var welfare = [
	'障害者雇用'
	,'年金・手帳'
	,'生活保護'
];
var other = [
	'劣等感・コンプレックス'
	,'変な癖'
	,'恐怖症'
	,'ニッチな感覚'
	,'コミュ障・ぼっち'
	,'ニート/無職'
	,'不登校/引きこもり'
	,'学生の悩み'
	,'仕事の悩み'
	,'いじめ'
];

var keyArr = [
	'都道府県'
	,'発達障害'
	,'メンタルヘルス'
	,'パーソナリティ障害'
	,'体の病'
	,'特異体質'
	,'lgbt'
	,'心理学'
	,'福祉'
	,'その他'
];

var valueArr = [todouhukenn, hattatu, mentalHealth, hspGited, lgbt, sinnrigaku, welfare, other];

function returnCategory(order){
	var category = [];

	if(order == 'object'){
		for(var i = 0; i < keyArr.length; i++){
			category[keyArr[i]] = valueArr[i]; 
		}
	}else{
		for(var y = 0; y < keyArr.length; y++){
			//array_mergeは配列同士を結合 array_pushは配列に要素を一つ入れる。
			$category.append($valueArr[y]);
		}
	}
	
	return category;
}

function getSmallCategory(argument) {
	for(var i = 0; i < keyArr.length; i++){
		if(keyArr[i] == argument){var bigCategoryNum = i}
	}
 	return valueArr[bigCategoryNum];
}

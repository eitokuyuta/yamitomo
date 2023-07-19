function imageUpload(canvas, arr) {
    //  @param arr[0] ファイル名
    //  @param arr[1] ファイルタイプ
    //
    
    //画像処理してformDataに追加
    if (canvas) {
        //canvasに描画したデータを取得
        var canvasImage = canvas;
                    
        //オリジナル容量(画質落としてない場合の容量)を取得
        var originalBinary = canvasImage.toDataURL(arr[1]); //画質落とさずバイナリ化
        var originalBlob = base64ToBlob(originalBinary); //オリジナル容量blobデータを取得
        //console.log(originalBlob["size"]);
                    
        //オリジナル容量blobデータをアップロード用blobに設定
        var uploadBlob = originalBlob;                    
                    
        //オリジナル容量が2MB以上かチェック
        if(2000000 <= originalBlob["size"]) {
            //2MB以下に落とす
            var capacityRatio = 2000000 / originalBlob["size"];
            var processingBinary = canvasImage.toDataURL(arr[1], capacityRatio); //画質落としてバイナリ化
            uploadBlob = base64ToBlob(processingBinary); //画質落としたblobデータをアップロード用blobに設定
            //console.log(capacityRatio);                        
            //console.log(uploadBlob["size"]);
        }
        const file = new File([uploadBlob], arr[0], { type: arr[1] })
        return file;
    }
}
 
// 引数のBase64の文字列をBlob形式にする
function base64ToBlob(base64) {
    var base64Data = base64.split(',')[1], // Data URLからBase64のデータ部分のみを取得
          data = window.atob(base64Data), // base64形式の文字列をデコード
          buff = new ArrayBuffer(data.length),
          arr = new Uint8Array(buff),
          blob,
          i,
          dataLen;
    // blobの生成
    for (i = 0, dataLen = data.length; i < dataLen; i++) {
        arr[i] = data.charCodeAt(i);
    }
    blob = new Blob([arr], {type: 'image/jpeg'});
    return blob;
}            
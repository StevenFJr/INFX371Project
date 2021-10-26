Object.prototype.getByIndex = function(index) {
    return this[Object.keys(this)[index]];
};

// function APICall(){
// request('GET','https://https://pro-api.coinmarketcap.com/v1/cryptocurrency/quotes/latest?CMC_PRO_API_KEY=' + apikey.key+'&symbol='+ document.getElementById('coininput').value.toUpperCase())
// .then((r1) => {
//     var x1 = JSON.parse(r1.target.responseText);
//     document.getElementById('infoArea').innerHTML=("<div> <p>"+x1.data[1027]+" "+x1.data[1027]+" </p> </div>");
// }).catch(err => {
//     console.log(err);
// })  
// }

// function APICall2(){
//     request('GET','https://pro-api.coinmarketcap.com/v1/cryptocurrency/quotes/latest?CMC_PRO_API_KEY=' + apikey.key+'&slug='+ document.getElementById('coininput2').value.toLowerCase())
//     .then((r1) => {
//         console.log(r1);
//         console.log(r1.target);
//         var x1 = JSON.parse(r1.target.responseText);
//         var x2=x1.data.getByIndex(0);
//         console.log(x2);
//         document.getElementById('infoArea').innerHTML=("<div> <p> Name: "+x2.name+" Price: "+x2.quote.USD.price+" </p> </div>");
//     })  
// }

function print(stringtoprint,location){
    let target = document.getElementById(location);
    target.innerText= target.innerText+" "+stringtoprint+"\n";
}

function decode(apiout){
    if(apiout != null){
        var x1 = JSON.parse(apiout);
        var x2=x1.data.getByIndex(0);
        print(x2.name,"NameResult");
        print(x2.quote.USD.price, "PriceResult");
        print(x2.quote.USD.market_cap, "MarketCapResult");
        print(x2.quote.USD.volume_24h, "VolumeResult");
        print(x2.quote.USD.percent_change_24h, "Change24Result");
        return x2;
    }
}

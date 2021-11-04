Object.prototype.getByIndex = function(index) {
    return this[Object.keys(this)[index]];
};

function printat(stringtoprint,location){
    let target = document.getElementById(location);
    target.innerText= target.innerText+" "+stringtoprint+"\n";
}

function decode(apiout){
    if(apiout != null){
        var x1 = JSON.parse(apiout);
        var x2=x1.data.getByIndex(0);
        printat(x2.name,"NameResult");
        printat(x2.quote.USD.price, "PriceResult");
        printat(x2.quote.USD.market_cap, "MarketCapResult");
        printat(x2.quote.USD.percent_change_1h, "Change1Result");
        printat(x2.quote.USD.percent_change_24h, "Change24Result");
        printat(x2.quote.USD.percent_change_7d, "Change7dResult");
        return x2;
    }
}

function decode2(apiout){
    if(apiout != null){
        var x1 = JSON.parse(apiout);
        console.log("Testing");
        console.log(x1);
        return x1;
    }
}

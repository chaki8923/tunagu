
exports.add = function(a,b){
  return a + b;
}

exports.multiply = function(a,b){
  return a * b;
}

exports.evenOrOdd = function(a){
  if(a % 2 == 0){
    return '偶数';
  }else{
    return '奇数';
  }
}
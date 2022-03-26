var assert = require('assert');
var calc = require('../calc.js');

describe('計算モジュールテスト',function(){
  it('1+1は2になる',function(){
    assert.equal(calc.add(1,1),2);
  });
  it('3*3は9になる',function(){
    assert.equal(calc.multiply(3,3),9);
  });
  it('evenOrOdd(4)は偶数が返ってくる',function(){
    assert.equal(calc.evenOrOdd(4),'偶数');
  });
  it('evenOrOdd(5)は奇数が返ってくる',function(){
    assert.equal(calc.evenOrOdd(5),'奇数');
  });
});
$(function () {

  $(window).on('scroll', function () {
    $('.header').toggleClass('scroll', $(this).scrollTop() > 100);
  });
  $(window).on('scroll', function () {
    $('.panel').toggleClass('slideup', $(this).scrollTop() > 200);
  });

  $('.bar').on('click', function () {
    $(this).find('.bar-top').toggleClass('active');
    $(this).find('.bar-middle').toggleClass('active');
    $(this).find('.bar-bottom').toggleClass('active');
    $('.sp-header').toggleClass('active');
  });
  $('.search-fix').on('click', function () {
    $('.sidebar').toggleClass('active');
  });
  //要素以外クリックでサイドバー閉じる
  $(document).on('click', function (e) {
    if (!$(e.target).closest('sp-header').length && !$(e.target).closest('.bar').length) {
      $('.sp-header').removeClass('active');
      $(this).find('.bar-top').removeClass('active');
      $(this).find('.bar-middle').removeClass('active');
      $(this).find('.bar-bottom').removeClass('active');

    }
  });
  $(document).on('click', function (e) {
    if (!$(e.target).closest('.sidebar').length && !$(e.target).closest('.search-fix').length) {
      $('.sidebar').removeClass('active');

    }
  });



  //====================================================================
  //slickスライダー
  //====================================================================

  $('.container-slider-img').slick({
    autoplay: true,
    dots: true,
    slidesToShow: 4,
    slidesToScroll: 1,
    //次へ、前へボタンスタイル編集
    prevArrow: '<button type=”button” class=slick-prev style="background: none; cursor: pointer;outline: none; border: none; ;z-index: 2; border: none;"><img src=img/arrow_prev.png></button>',
    nextArrow: '<button type=”button” class=slick-next style="background: none; cursor: z-index: 2; "><img src=img/arrow_next.png></button>',
    dotsClass: 'dots',
    infinite: true,
    centerMode: false,
    centerPadding: ''
  });
  $('.container-slider-sp').slick({
    autoplay: false,
    dots: true,
    slidesToShow: 1,
    slidesToScroll: 1,
    //次へ、前へボタンスタイル編集
    prevArrow: '<button type=”button” class=slick-prev style="background: none; cursor: pointer;outline: none; border: none; ;z-index: 2; border: none;"><img src=img/arrow_prev.png></button>',
    nextArrow: '<button type=”button” class=slick-next style="background: none; cursor: z-index: 2; "><img src=img/arrow_next.png></button>',
    dotsClass: 'dots',
    infinite: true,
    centerMode: false,
    centerPadding: ''
  });


  //====================================================================
  //====================================================================
  $('.signup-form-drop').on('dragover', function () {
    $(this).css('border', '#000 2px dashed');
  });
  $('.signup-form-drop').on('dragleave', function () {
    $(this).css('border', 'none');
  });


  $('#crear').on('click', function () {
    $('.prevew').attr("src", '');
    $('.prevew').val('');
    $('.prevew').css('display', 'none');
  });

  $('.signup-form-picbox').on('change', function () {
    $('.prevew').css('display', 'block');
    $('.signup-form-drop').css('border', 'none');

    $file = this.files[0];
    $img = $(this).next();

    var read = new FileReader();
    read.onload = function (e) {

      $img.attr("src", e.target.result);
    }

    read.readAsDataURL($file);
  });

  $('.err_modal').on('click', function () {
    $('body,html').animate({ scrollTop: 2100 }, 500);
    return false
  });

  var msg = $('.toggle_msg').text();

  if (msg.replace(/\s+/g, "").length > 1) {
    $('.toggle_msg').slideToggle('slow');
    setTimeout(function () { $('.toggle_msg').slideToggle() }, 3000);
  }

  $('.textarea').on('keyup', function () {
    $text = $(this).val().replace(/\s+/g, "").length;
    $('.js-text-count').text($text);
  });

  var $like,
    likeId;


  $like = $('.js-heart');
  $used = $('.js-used');

  //ajaxのidは小文字。Idとかだめ。次回はいいね押した時の処理をやっていく。
  likeId = $like.data('likeid');
  usedId = $used.data('caseid');


  //いいね押す
  $like.on('click', function () {
    var $this = $(this);
    console.log('クリックされた');
    // console.log(likeId);

    $.ajax({
      type: "post",
      url: "like.php",
      data: { likeID: likeId }//{$_POSTに渡す配列名:id属性の数字}
    }).done(function (ok) {

      console.log(ok);
      if (!$this.hasClass('active')) {
        $this.addClass('wave');
      } else {
        $this.removeClass('wave');
      }
      $this.toggleClass('active');
      $('.like_num').toggleClass('beta');

      $like_num = Number(ok);

      $('.like_num').html($like_num);

    }).fail(function (ng) {
      console.log('ノットアジャス')
    });
  });

  //使いまわした押す
  $used.on('click', function () {
    var $this = $(this);
    console.log('回された');
    // console.log(likeId);

    $.ajax({
      type: "post",
      url: "used.php",
      data: { caseID: usedId }
    }).done(function (data) {

      if (!$this.hasClass('active')) {
        $this.addClass('role');
      } else {
        $this.removeClass('role');
      }

      console.log('ajax成功');
      console.log(data);
      $this.toggleClass('active');
      $use_num = Number(data);
      $('.use_num').toggleClass('beta');

      $('.use_num').html($use_num);
    }).fail(function (ng) {
      console.log('ノットアジャス')
    });
  });


  //いいね出したり引っ込めたり

  $('.js-iine-btn').on('click', function () {

    $('.iine-user').toggleClass('panish');
    $('.iine-close').toggleClass('active');
    $('.slide-iine').toggleClass('slidein');
  });
  $('.js-used-btn').on('click', function () {

    $('.used-user').toggleClass('panish');
    $('.used-close').toggleClass('active');
    $('.slide-use').toggleClass('slidein');
  });


  //いいね数で検索して条件つけた場合
  $('.iine-select').blur(function () {

    $iine_val = $(this).val();
    console.log('セレクトの中身' + $iine_val);
    if ($iine_val >= 1) {
      $('.new-select').prop('disabled', 'true');
      $('.used-select').prop('disabled', 'true');
      $('.new-select').val(0);
      $('.used-select').val(0);

    } else {
      console.log('あ');
      $('.new-select').prop('disabled', false);
      $('.used-select').prop('disabled', false);

    }
  });
  //使い回し数で検索して条件つけた場合
  $('.used-select').blur(function () {

    $used_val = $(this).val();

    if ($used_val >= 1) {
      $('.new-select').prop('disabled', 'true');
      $('.iine-select').prop('disabled', 'true');
      $('.new-select').val(0);
      $('.iine-select').val(0);

    } else {
      console.log('あ');
      $('.new-select').prop('disabled', false);
      $('.iine-select').prop('disabled', false);

    }
  });
  //新着順で検索して条件つけた場合
  $('.new-select').blur(function () {

    $new_val = $(this).val();

    if ($new_val >= 1) {
      $('.used-select').prop('disabled', 'true');
      $('.iine-select').prop('disabled', 'true');
      $('.used-select').val(0);
      $('.iine-select').val(0);

    } else {
      console.log('あ');
      $('.used-select').prop('disabled', false);
      $('.iine-select').prop('disabled', false);

    }
  });

  //======================================================================
  //検索窓のやつ削除
  //======================================================================
  $('.clear-val').on('click', function () {
    $('.js-select').val('');
  });
  //======================================================================
  //アラート
  $('.delete').on('click', function () {
    return confirm('接客事例を削除して宜しいですか？');
  })
  //======================================================================



  //======================================================================
  //ローディング
  //======================================================================

  $(window).on('load', function () { //全ての読み込みが完了したら実行
    $('#loading').delay(1000).fadeOut('slow');
  });

  //10秒たったら強制的にロード画面を非表示
  $(function () {
    setTimeout(stopload, 3000);
  });

  function stopload() {
    $('#loading').fadeOut(300);
  }

  //文字数オーバー
  $('.textarea').on('keyup', function () {
    let val = $(this).val().length;
    if (val >= 1000) {
      $('.js-fontlength-err').show()
    } else (
      $('.js-fontlength-err').hide()

    )

  })

});
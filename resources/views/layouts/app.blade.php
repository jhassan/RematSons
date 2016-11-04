<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- styles -->
    <link href="/css/styles.css" rel="stylesheet">
    <link href="/css/jquery-ui.min.css" rel="stylesheet">

    <link href="/css/font-awesome.css" rel="stylesheet">
    <link href="/vendors/form-helpers/css/bootstrap-formhelpers.min.css" rel="stylesheet">
    <link href="/vendors/select/bootstrap-select.min.css" rel="stylesheet">
    <link href="/vendors/tags/css/bootstrap-tags.css" rel="stylesheet">
    <link href="/vendors/bootstrap-datetimepicker/datetimepicker.css" rel="stylesheet">

    <link href="/css/forms.css" rel="stylesheet">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Rehmat and Sons') }}</title>

    <!-- Styles -->
    <!-- <link href="/css/app.css" rel="stylesheet"> -->

    <!-- Scripts -->
    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>
    </script>
</head>
<body>
    <div class="header">
         <div class="container">
            <div class="row">
               <div class="col-md-5">
                  <!-- Logo -->
                  <div class="logo">
                     <h1><a href="/home">Rehmat and Sons</a></h1>
                  </div>
               </div>
               @if (Auth::check())
               <div class="col-md-2 pull-right">
                  <div class="navbar navbar-inverse" role="banner">
                      <nav class="collapse navbar-collapse bs-navbar-collapse navbar-right" role="navigation">
                        <ul class="nav navbar-nav">
                          <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"> {{ Auth::user()->name }} <b class="caret"></b></a>
                            <ul class="dropdown-menu animated fadeInUp">
                                <li>
                                <a href="{{ url('/logout') }}"
                                    onclick="event.preventDefault();
                                             document.getElementById('logout-form').submit();">
                                    Logout
                                </a>

                                <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                                    {{ csrf_field() }}
                                </form>
                                </li>
                            </ul>
                          </li>
                        </ul>
                      </nav>
                  </div>
               </div>
               @endif
            </div>
         </div>
    </div>

    <div class="page-content">
        <div class="row">
          <div class="col-md-3">
            <!-- @if (Auth::check()) -->
            <div class="sidebar content-box" style="display: block;">
                <ul class="nav">
                    <!-- Main menu -->
                    <li class="current"><a href="/home"><i class="glyphicon glyphicon-home"></i> Dashboard</a></li>
                    <li class="submenu current {{ Request::is('parties') ? 'open' : '' }} {{ Request::is('parties/add') ? 'open' : '' }}">
                         <a href="#">
                            <i class="glyphicon glyphicon-list"></i> Party Management
                            <span class="caret pull-right"></span>
                         </a>
                         <!-- Sub menu -->
                         <ul>
                            <li class="current"><a href="/parties/add">Add Party</a></li>
                            <li><a href="/parties">View/Edit Party</a></li>
                        </ul>
                    </li>
                    <li class="submenu current {{ Request::is('products') ? 'open' : '' }} {{ Request::is('products/add') ? 'open' : '' }}">
                         <a href="#">
                            <i class="glyphicon glyphicon-list"></i> products Management
                            <span class="caret pull-right"></span>
                         </a>
                         <!-- Sub menu -->
                         <ul>
                            <li class="current"><a href="/products/add">Add Product</a></li>
                            <li><a href="/products">View/Edit Product</a></li>
                        </ul>
                    </li>
                    <li class="submenu current {{ Request::is('purchase_stock') ? 'open' : '' }} {{ Request::is('purchase_stock/add') ? 'open' : '' }}">
                         <a href="#">
                            <i class="glyphicon glyphicon-list"></i> Stock Purchase Management
                            <span class="caret pull-right"></span>
                         </a>
                         <!-- Sub menu -->
                         <ul>
                            <li class="current"><a href="/purchase_stock/add">Add Stock Purchase</a></li>
                            <li><a href="/purchase_stock">View/Edit Stock Purchase</a></li>
                        </ul>
                    </li>
                    <li class="submenu current {{ Request::is('sale_stock') ? 'open' : '' }} {{ Request::is('sale_stock/add') ? 'open' : '' }}">
                         <a href="#">
                            <i class="glyphicon glyphicon-list"></i> Stock Sale Management
                            <span class="caret pull-right"></span>
                         </a>
                         <!-- Sub menu -->
                         <ul>
                            <li class="current"><a href="/sale_stock/add">Add Stock Sale</a></li>
                            <li><a href="/sale_stock">View/Edit Stock Purchase</a></li>
                        </ul>
                    </li>
                    <li class="submenu current {{ Request::is('list_price') ? 'open' : '' }}">
                         <a href="#">
                            <i class="glyphicon glyphicon-list"></i> List Price Management
                            <span class="caret pull-right"></span>
                         </a>
                         <!-- Sub menu -->
                         <ul>
                            <li class="current"><a href="/list_price">Update List Price</a></li>
                        </ul>
                    </li>
                    <li class="submenu current {{ Request::is('list_transections') ? 'open' : '' }}">
                         <a href="#">
                            <i class="glyphicon glyphicon-list"></i> Accounts Management
                            <span class="caret pull-right"></span>
                         </a>
                         <!-- Sub menu -->
                         <ul>
                            <li class="current"><a href="/accounts/list_transections">List of Transections</a></li>
                        </ul>
                    </li>
                    <li class="submenu current {{ Request::is('reports') ? 'open' : '' }} {{ Request::is('reports/view_purchase_stock') ? 'open' : '' }} {{ Request::is('reports/view_sale_stock') ? 'open' : '' }} {{ Request::is('reports/view_today_stock') ? 'open' : '' }}">
                         <a href="#">
                            <i class="glyphicon glyphicon-list"></i> Reports Management
                            <span class="caret pull-right"></span>
                         </a>
                         <!-- Sub menu -->
                         <ul>
                            <li class="current"><a href="/reports/view_purchase_stock">Purchase Stock</a></li>
                            <li><a href="/reports/view_sale_stock">Sale Stock</a></li>
                            <li><a href="/reports/view_today_stock">Today Stock</a></li>
                        </ul>
                    </li>
                </ul>
             </div>
             <!-- @endif -->
          </div>
          @yield('content')
        </div>
    </div>

    <footer>
         <div class="container">
         
            <div class="copy text-center">
               Copyright {{ date("Y") }} <a href='#'>Rehmat and Sons</a>
            </div>
            
         </div>
      </footer>

    <!-- Scripts -->
    <!-- <script src="/js/app.js"></script> -->
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="/js/jquery.js"></script>
    <script src="/js/jquery-ui.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="/bootstrap/js/bootstrap.min.js"></script>
    <script src="/vendors/form-helpers/js/bootstrap-formhelpers.js"></script>

    <script src="/vendors/select/bootstrap-select.min.js"></script>

    <script src="/vendors/tags/js/bootstrap-tags.min.js"></script>

    <script src="/vendors/mask/jquery.maskedinput.min.js"></script>

    <script src="/vendors/moment/moment.min.js"></script>

    <script src="/vendors/wizard/jquery.bootstrap.wizard.min.js"></script>

     <!-- bootstrap-datetimepicker -->
     <script src="/vendors/bootstrap-datetimepicker/bootstrap-datetimepicker.js"></script> 


    <link href="/css/bootstrap-editable.css" rel="stylesheet"/>
    <script src="/js/bootstrap-editable.min.js"></script>

    <script src="/js/forms.js"></script>
    <script src="/js/custom.js"></script>
    <script type="text/javascript">
      $(document).ready(function () {
      $(".date-picker").datepicker('setDate', new Date());
      $(".form_datetime").datetimepicker('setDate', new Date());  
      // Numeric only control handler
      jQuery.fn.ForceNumericOnly =
      function()
      {
        return this.each(function()
        {
          $(this).keydown(function(e)
          {
            var key = e.charCode || e.keyCode || 0;
            // allow backspace, tab, delete, enter, arrows, numbers and keypad numbers ONLY
            // home, end, period, and numpad decimal
            return (
              key == 8 || 
              key == 9 ||
              key == 13 ||
              key == 46 ||
              key == 110 ||
              key == 190 ||
              (key >= 35 && key <= 40) ||
              (key >= 48 && key <= 57) ||
              (key >= 96 && key <= 105));
          });
        });
      };   
      
      // Call Only numbers
      $(".number_only").ForceNumericOnly();
      
      // Keypress add commas in numbers
       $('input.number_only').keyup(function(event){
          // skip for arrow keys
          if(event.which >= 37 && event.which <= 40){
            event.preventDefault();
          }
          var $this = $(this);
          var num = $this.val().replace(/,/gi, "").split("").reverse().join("");
          var num2 = RemoveRougeChar(num.replace(/(.{3})/g,"$1,").split("").reverse().join(""));
          // the following line has been simplified. Revision history contains original.
          $this.val(num2);
        });
      });
      function RemoveRougeChar(convertString)
      {
        if(convertString.substring(0,1) == ",")
        {
          return convertString.substring(1, convertString.length)            
        }
        return convertString;
      }
    </script>
    @yield('custom_js')
</body>
</html>

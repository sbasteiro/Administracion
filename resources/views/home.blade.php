<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="apple-touch-icon" href="/img/favicon.png">
    <link rel="icon" href="/img/favicon.png">
    <title>Administrado Challenge</title>
    <!-- Bootstrap core CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/d50f1b5cb8.js" crossorigin="anonymous"></script>
    <meta name="theme-color" content="#7952b3">
    <!-- Custom styles for this template -->
    <link href="css/dashboard.css" rel="stylesheet">
</head>

<body>
    <header class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
        <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3" href="/">
            <img class="logo" src="/img/logo.png">
            Administrado Challenge
        </a>
        <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <form method="GET" style="display: contents;">
            <input name="q" class="form-control form-control-dark w-100" type="text" placeholder="Búsqueda por dirección / ID / nombre del comprador" aria-label="Búsqueda por ID / proudcto / dirección / nombre del comprador" value="{{ $to_search }}">
            <input type="hidden" name="context" value="{{ $context }}">
            <input type="hidden" name="order_by" value="{{ $order_by }}">
            <input type="submit" style="position: absolute; left: -9999px"/>
        </form>
    </header>
    <div class="container-fluid">
        <div class="row">
            <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
                <div class="position-sticky pt-3">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link @if ($context == 'pending') active @endif" href="/?context=pending">
                                <span data-feather="file"></span>
                                <i class="fa fa-dolly me-2"></i> Envíos pendientes ({{ $pending_count }})
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link @if ($context == 'delivered') active @endif" href="/?context=delivered">
                                <span data-feather="shopping-cart"></span>
                                <i class="fa fa-thumbs-up me-2"></i> Envíos entregados ({{ $delivered_count }})
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link @if ($context == 'deleted') active @endif" href="/?context=deleted">
                                <span data-feather="users"></span>
                                <i class="fa fa-trash me-2"></i> Envíos borrados ({{ $deleted_count }})
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Mis envíos</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <div class="btn-group me-2">
                            <a type="button" class="btn btn-sm btn-outline-secondary @if ($order_by == 'desc') active @endif"
                               href="/?q={{ $to_search }}&context={{ $context }}&order_by=desc">
                                <i class="fa fa-sort-alpha-up me-1"></i>
                                Más nuevos
                            </a>
                            <a type="button" class="btn btn-sm btn-outline-secondary @if ($order_by == 'asc') active @endif"
                               href="/?q={{ $to_search }}&context={{ $context }}&order_by=asc">
                            <i class="fa fa-sort-alpha-down-alt me-1"></i>
                                Más viejos
                            </a>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Estado</th>
                                <th>Producto</th>
                                <th>Dirección</th>
                                <th>Comprador</th>
                                <th>Zona</th>
                                <th>Acciónes</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($orders_shipping as $shipping)
                            <tr>
                                <td>{{ $shipping['id_shipping'] }}</td>
                                <td>
                                    @if ($shipping['status'] == 'pending')
                                        <span class="badge bg-secondary">Pendiente</span>
                                    @elseif ($shipping['status'] == 'delivered')
                                        <span class="badge bg-success">Entregado</span>
                                    @else
                                        <span class="badge bg-danger">Borrado</span>
                                    @endif
                                </td>
                                <td>
                                  <img class="product-image" src="{{ $shipping['photo_url'] }}"/>
                                    {{ $shipping['description'] }}
                                </td>
                                <td>{{ $shipping['address'] }}</td>
                                <td>{{ $shipping['buyer_name'] }}</td>
                                <td>{{ $shipping['zone'] }}</td>
                                <td>
                                    @if ($shipping['status'] == 'pending')
                                        <button class="btn btn-sm btn-success mb-1" onclick="shippingUpdate({{ $shipping['id'] }})">Ya lo entregué</button>
                                        <button onclick="shippingDelete({{ $shipping['id'] }})" class="btn btn-sm btn-danger mb-1">Borrar</button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                        {!! $orders_shipping->links() !!}
                </div>
            </main>

        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.js" integrity="sha384-uO3SXW5IuS1ZpFPKugNNWqTZRRglnUJK6UAZ/gxOX80nxEkN9NcGZTftn6RzhGWE" crossorigin="anonymous"></script>
    <script src="js/app.js"></script>
    <script src="js/jquery.js"></script>
</body>

</html>

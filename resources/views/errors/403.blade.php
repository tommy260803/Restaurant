<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso Denegado</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Poppins', sans-serif;
        }
        
        .error-container {
            text-align: center;
            padding: 40px;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            max-width: 600px;
            animation: fadeInUp 0.8s ease-out;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .error-icon {
            font-size: 100px;
            color: #dc3545;
            margin-bottom: 20px;
            animation: shake 0.5s ease-in-out;
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-10px); }
            75% { transform: translateX(10px); }
        }
        
        .error-code {
            font-size: 80px;
            font-weight: bold;
            color: #667eea;
            margin: 0;
            line-height: 1;
        }
        
        .error-title {
            font-size: 28px;
            font-weight: 600;
            color: #333;
            margin: 20px 0 15px 0;
        }
        
        .error-message {
            font-size: 16px;
            color: #666;
            margin-bottom: 30px;
            line-height: 1.6;
        }
        
        .btn-back {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 12px 30px;
            font-size: 16px;
            font-weight: 600;
            border-radius: 50px;
            color: white;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .btn-back:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.4);
            color: white;
        }
        
        .btn-back i {
            font-size: 20px;
        }
        
        .info-box {
            background: #f8f9fa;
            border-left: 4px solid #667eea;
            padding: 15px;
            border-radius: 8px;
            margin-top: 20px;
            text-align: left;
        }
        
        .info-box strong {
            color: #667eea;
        }
        
        .user-info {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 10px 20px;
            background: #e7f3ff;
            border-radius: 50px;
            margin-top: 15px;
            font-size: 14px;
            color: #0066cc;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-icon">
            <i class='bx bx-block'></i>
        </div>
        
        <h1 class="error-code">403</h1>
        <h2 class="error-title">Acceso Denegado</h2>
        <p class="error-message">
            Lo sentimos, no tienes los permisos necesarios para acceder a esta sección del sistema.
        </p>
        
        @auth
            <div class="user-info">
                <i class='bx bx-user-circle' style="font-size: 24px;"></i>
                <span>Conectado como: <strong>{{ auth()->user()->nombre_usuario }}</strong></span>
            </div>
        @endauth
        
        <div class="info-box">
            <strong>¿Por qué veo esto?</strong>
            <p class="mb-0 mt-2">
                Esta página requiere permisos especiales que tu cuenta de usuario no tiene asignados. 
                Si crees que deberías tener acceso, contacta al administrador del sistema.
            </p>
        </div>
        
        <div class="mt-4">
            <a href="{{ route('home') }}" class="btn-back">
                <i class='bx bx-home'></i>
                Volver al Inicio
            </a>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

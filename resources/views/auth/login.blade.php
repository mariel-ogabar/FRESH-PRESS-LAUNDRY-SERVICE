<x-guest-layout>
    <style>
        body {
            background-color: #f8fafc; 
            font-family: 'Inter', -apple-system, sans-serif;
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
        }

        .page-wrapper {
            max-width: 1100px;
            margin: 0 auto;
            padding: 40px 20px; 
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
        }

        .logo {
            font-size: 32px;
            color: #334155; 
            font-weight: 500;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.15em;
        }

        .subtitle {
            font-size: 10px;
            color: #64748b;
            max-width: 500px;
            margin: 0 auto;
            line-height: 1.6;
            text-transform: uppercase;
            font-weight: 600;
            letter-spacing: 0.2em;
        }

        .info-cards {
            display: flex;
            justify-content: center;
            gap: 24px;
            margin-bottom: 40px;
            flex-wrap: wrap;
        }

        .card {
            background: #ffffff;
            width: 260px;
            padding: 32px 24px;
            border-radius: 24px; 
            text-align: center;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            border: 1px solid #f1f5f9;
        }

        .card-title {
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.2em;
            color: #7c4dff; 
            margin-bottom: 8px;
        }

        .card-text {
            font-size: 13px;
            color: #475569;
            line-height: 1.5;
            font-weight: 500;
        }

        .login-container {
            display: flex;
            justify-content: center;
            padding-bottom: 60px;
        }

        .login-box {
            background: #ffffff;
            width: 100%;
            max-width: 400px;
            padding: 40px;
            border-radius: 32px; 
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.08);
            border: 1px solid #f1f5f9;
        }

        .login-header {
            font-size: 20px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.15em;
            color: #1e293b;
            margin-bottom: 24px;
            text-align: center;
        }

        .tabs {
            display: flex;
            background: #f1f5f9;
            border-radius: 14px;
            padding: 6px;
            margin-bottom: 24px;
        }

        .tab {
            flex: 1;
            padding: 10px 0;
            text-align: center;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: #64748b;
            text-decoration: none;
            border-radius: 10px;
            transition: all 0.2s ease;
        }

        .tab.active {
            background: #ffffff;
            color: #7c4dff;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }

        .form-group {
            margin-bottom: 16px;
        }

        .form-group input {
            width: 100%;
            padding: 12px 16px;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            font-size: 14px;
            background: #f8fafc;
            box-sizing: border-box;
            transition: all 0.2s ease;
        }

        .form-group input:focus {
            outline: none;
            border-color: #7c4dff;
            background: #ffffff;
            box-shadow: 0 0 0 4px rgba(124, 77, 255, 0.1);
        }

        .error {
            font-size: 11px;
            color: #ef4444;
            margin-top: 6px;
            font-weight: 500;
        }

        .login-footer {
            text-align: center;
            margin-top: 24px;
            font-size: 11px;
        }

        .login-footer a {
            color: #94a3b8;
            text-decoration: none;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            transition: color 0.2s;
        }

        .login-footer a:hover {
            color: #7c4dff;
        }
    </style>

    <div class="page-wrapper">
        {{-- Header Section --}}
        <div class="header">
            <div class="logo">FreshPress</div>
            <div class="subtitle">
                Premium care for your garments with professional pickup and delivery
            </div>
        </div>

        {{-- Contact Info Cards --}}
        <div class="info-cards">
            <div class="card">
                <div class="card-title">Location</div>
                <div class="card-text">
                    4A Mall, Campus Area<br>Main Street
                </div>
            </div>

            <div class="card">
                <div class="card-title">Connect</div>
                <div class="card-text">
                    09123456789<br>
                    Mon – Sat: 8AM – 8PM
                </div>
            </div>

            <div class="card">
                <div class="card-title">Support</div>
                <div class="card-text">
                    FreshPress@laundry.com
                </div>
            </div>
        </div>

        {{-- Login Container --}}
        <div class="login-container">
            <div class="login-box">
                <div class="login-header">Member Access</div>

                <div class="tabs">
                    <a href="{{ route('login') }}" class="tab active">Login</a>
                    <a href="{{ route('register') }}" class="tab">Sign Up</a>
                </div>

                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="form-group">
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="EMAIL ADDRESS" required autofocus>
                        @error('email') <div class="error">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <input type="password" name="password" placeholder="PASSWORD" required>
                        @error('password') <div class="error">{{ $message }}</div> @enderror
                    </div>

                    <div style="margin-bottom: 20px; font-size: 11px; color: #64748b; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em;">
                        <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                            <input type="checkbox" name="remember" style="width: 16px; height: 16px; accent-color: #7c4dff; border-radius: 4px;">
                            Remember me
                        </label>
                    </div>

                    {{-- Primary Action Button (Standardized) --}}
                    <div class="flex justify-center mt-6">
                        <x-primary-button type="submit" class="!w-full !justify-center !py-4 !rounded-full !text-[11px] !font-black !uppercase !tracking-widest !bg-[#7c4dff] shadow-lg shadow-indigo-100 transition-all hover:scale-[1.02] active:scale-95">
                            {{ __('Sign In') }}
                        </x-primary-button>
                    </div>

                    <div class="login-footer">
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}">Forgot your password?</a>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
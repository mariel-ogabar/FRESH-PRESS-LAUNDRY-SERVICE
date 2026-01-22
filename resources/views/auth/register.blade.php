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

        .register-container {
            display: flex;
            justify-content: center;
            padding-bottom: 60px;
        }

        .register-box {
            background: #ffffff;
            width: 100%;
            max-width: 420px; 
            padding: 40px;
            border-radius: 32px; 
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.08);
            border: 1px solid #f1f5f9;
        }

        .register-header {
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
            margin-bottom: 14px;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 12px 16px;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            font-size: 14px;
            background: #f8fafc;
            box-sizing: border-box;
            font-family: inherit;
            transition: all 0.2s ease;
            resize: none;
        }

        .form-group input:focus,
        .form-group textarea:focus {
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

        .footer-link {
            text-align: center;
            margin-top: 24px;
            font-size: 11px;
        }

        .footer-link a {
            color: #94a3b8;
            text-decoration: none;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            transition: color 0.2s;
        }

        .footer-link a:hover {
            color: #7c4dff;
        }
    </style>

    <div class="page-wrapper">
        {{-- Header Section --}}
        <div class="header">
            <div class="logo">FreshPress</div>
            <div class="subtitle">
                Join our premium laundry community and experience professional care
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

        {{-- Register Container --}}
        <div class="register-container">
            <div class="register-box">
                <div class="register-header">Create Account</div>

                <div class="tabs">
                    <a href="{{ route('login') }}" class="tab">Login</a>
                    <a href="{{ route('register') }}" class="tab active">Sign Up</a>
                </div>

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <div class="form-group">
                        <input type="text" name="name" placeholder="FULL NAME" value="{{ old('name') }}" required autofocus>
                        @error('name') <div class="error">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <input type="email" name="email" placeholder="EMAIL ADDRESS" value="{{ old('email') }}" required>
                        @error('email') <div class="error">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <input type="password" name="password" placeholder="PASSWORD" required>
                        @error('password') <div class="error">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <input type="password" name="password_confirmation" placeholder="CONFIRM PASSWORD" required>
                    </div>

                    <div class="form-group">
                        <input type="text" name="contact_no" placeholder="CONTACT NUMBER" value="{{ old('contact_no') }}" required>
                        @error('contact_no') <div class="error">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <textarea name="address" rows="2" placeholder="COMPLETE ADDRESS" required>{{ old('address') }}</textarea>
                        @error('address') <div class="error">{{ $message }}</div> @enderror
                    </div>

                    {{-- Primary Action Button (Standardized Pill Style) --}}
                    <div class="flex justify-center mt-6">
                        <x-primary-button type="submit" class="!w-full !justify-center !py-4 !rounded-full !text-[11px] !font-black !uppercase !tracking-widest !bg-[#7c4dff] shadow-lg shadow-indigo-100 transition-all hover:scale-[1.02] active:scale-95">
                            {{ __('Register Now') }}
                        </x-primary-button>
                    </div>

                    <div class="footer-link">
                        <a href="{{ route('login') }}">
                            Already registered? Log in here
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
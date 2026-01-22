<x-app-layout>
    <style>
        body {
            margin: 0;
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
            color: #334155;
            -webkit-font-smoothing: antialiased;
        }

        .container {
            max-width: 1200px;
            margin: auto;
            padding: 40px 20px;
        }

        /* HERO */
        .hero {
            background: #7c4dff;
            color: #fff;
            border-radius: 40px;
            padding: 60px 40px;
            text-align: center;
            margin-bottom: 30px;
            box-shadow: 0 25px 50px -12px rgba(124, 77, 255, 0.2);
        }

        .hero h1 {
            margin: 0 0 12px;
            font-size: 34px;
            font-weight: 600; 
            text-transform: uppercase;
            letter-spacing: 0.1em;
            line-height: 1.2;
        }

        .hero p {
            font-size: 12px;
            font-weight: 400; 
            text-transform: uppercase;
            letter-spacing: 0.2em;
            opacity: 0.85;
            margin-bottom: 30px;
        }

        /* FEATURES */
        .features {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }

        .feature {
            background: #fff;
            border-radius: 32px;
            padding: 40px 20px;
            text-align: center;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.03);
            border: 1px solid #f1f5f9;
        }

        .feature h4 {
            margin-bottom: 8px;
            font-size: 11px;
            font-weight: 600; 
            text-transform: uppercase;
            letter-spacing: 0.18em;
            color: #1e293b;
        }

        .feature p {
            font-size: 12px;
            font-weight: 400;
            color: #64748b;
            line-height: 1.5;
        }

        /* HOW IT WORKS */
        .how {
            background: #fff;
            border-radius: 32px;
            padding: 50px 25px;
            margin-bottom: 30px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.03);
            border: 1px solid #f1f5f9;
        }

        .how strong {
            display: block;
            text-align: center;
            font-size: 18px;
            font-weight: 500; 
            text-transform: uppercase;
            letter-spacing: 0.15em;
            color: #334155;
            margin-bottom: 40px;
        }

        .steps {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        .step {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .step span {
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f5f0ff;
            color: #7c4dff;
            width: 44px;
            height: 44px;
            border-radius: 50%;
            font-weight: 600;
            margin-bottom: 16px;
            font-size: 14px;
        }

        .step h4 {
            font-size: 14px;
            font-weight: 600; 
            color: #1e293b;
            margin-bottom: 6px;
        }

        .step p {
            font-size: 12px;
            font-weight: 400;
            color: #64748b;
            line-height: 1.5;
            max-width: 220px;
        }

        /* CTA */
        .cta {
            background: #f1f5f9;
            border-radius: 40px;
            padding: 50px 30px;
            text-align: center;
            border: 1px solid #e2e8f0;
        }

        .cta h3 {
            margin-bottom: 10px;
            font-size: 22px;
            font-weight: 600; 
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: #1e293b;
        }

        .cta p {
            font-size: 13px;
            font-weight: 400;
            color: #64748b;
            margin-bottom: 30px;
        }

        .cta-buttons {
            display: flex;
            justify-content: center;
            gap: 15px;
        }

        @media (max-width: 900px) {
            .features, .steps {
                grid-template-columns: 1fr;
                gap: 30px;
            }
        }
    </style>

    <div class="container">

        <div class="hero">
            <h1>Welcome to FreshPress Laundry</h1>
            <p>Your trusted partner for professional laundry and dry cleaning services</p>
            <x-primary-button onclick="window.location='{{ route('orders.create') }}'" class="!bg-white !text-[#7c4dff] hover:!bg-slate-50 !py-3 !px-10 !rounded-full !text-[11px] !font-bold !uppercase !tracking-[0.2em] shadow-xl">
                Book a Service now
            </x-primary-button>
        </div>

        <div class="features">
            <div class="feature">
                <svg class="block mx-auto mb-4" width="45" height="34" viewBox="0 0 51 38" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M25.5 9.49996V19L34 22.1666M46.75 19C46.75 27.7445 37.2361 34.8333 25.5 34.8333C13.7639 34.8333 4.25 27.7445 4.25 19C4.25 10.2555 13.7639 3.16663 25.5 3.16663C37.2361 3.16663 46.75 10.2555 46.75 19Z" stroke="#7c4dff" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <h4>Fast Service</h4>
                <p>Quick turnaround with express options available</p>
            </div>

            <div class="feature">
                <svg class="block mx-auto mb-4" width="58" height="34" viewBox="0 0 66 38" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M44 25.3333V4.75H2.75V25.3333H44ZM44 25.3333H63.25V17.4167L55 12.6667H44V25.3333ZM22 29.2917C22 31.4778 18.922 33.25 15.125 33.25C11.328 33.25 8.25 31.4778 8.25 29.2917C8.25 27.1055 11.328 25.3333 15.125 25.3333C18.922 25.3333 22 27.1055 22 29.2917ZM57.75 29.2917C57.75 31.4778 54.672 33.25 50.875 33.25C47.078 33.25 44 31.4778 44 29.2917C44 27.1055 47.078 25.3333 50.875 25.3333C54.672 25.3333 57.75 27.1055 57.75 29.2917Z" stroke="#7c4dff" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <h4>Free pick up & delivery</h4>
                <p>Convenient pickup and delivery at your doorstep</p>
            </div>

            <div class="feature">
                <svg class="block mx-auto mb-4" width="54" height="40" viewBox="0 0 60 44" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M30 40.3333C30 40.3333 50 33 50 22V9.16663L30 3.66663L10 9.16663V22C10 33 30 40.3333 30 40.3333Z" stroke="#7c4dff" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <h4>Quality Guaranteed</h4>
                <p>Professional care for all your garments</p>
            </div>

            <div class="feature">
                <svg class="block mx-auto mb-4" width="52" height="40" viewBox="0 0 58 44" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M29.0002 3.66663L36.4677 15.1433L53.1668 16.995L41.0835 25.9233L43.9352 38.5366L29.0002 32.5783L14.0652 38.5366L16.9168 25.9233L4.8335 16.995L21.5327 15.1433L29.0002 3.66663Z" stroke="#7c4dff" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <h4>Trusted Service</h4>
                <p>Rated 5 stars by our satisfied customers</p>
            </div>
        </div>

        <div class="how">
            <strong>How it works</strong>
            <div class="steps">
                <div class="step">
                    <span>1</span>
                    <h4>Book your service</h4>
                    <p>Select your preferred treatment and schedule a convenient pickup time.</p>
                </div>
                <div class="step">
                    <span>2</span>
                    <h4>Expert Cleaning</h4>
                    <p>Our professionals clean your items with premium products and care.</p>
                </div>
                <div class="step">
                    <span>3</span>
                    <h4>Ready for Delivery</h4>
                    <p>Receive your fresh, clean laundry delivered right back to your door.</p>
                </div>
            </div>
        </div>

        <div class="cta">
            <h3>Ready to get started?</h3>
            <p>View our services and pricing, then book your first order today</p>
            <div class="cta-buttons">
                <x-primary-button onclick="window.location='{{ route('services.index') }}'" class="!py-3 !px-10 !rounded-full !text-[11px] !font-bold !uppercase !tracking-widest">
                    View Services
                </x-primary-button>
                @auth
                    <x-primary-button onclick="window.location='{{ route('dashboard') }}'" class="!bg-white !text-[#7c4dff] !py-3 !px-10 !rounded-full !text-[11px] !font-bold !uppercase !tracking-widest border border-slate-200">
                        My Orders
                    </x-primary-button>
                @endauth
            </div>
        </div>

    </div>
</x-app-layout>
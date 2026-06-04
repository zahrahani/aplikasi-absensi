# presensi
lib/    
├── main.dart              # Tempat untuk inisiasi
├── app.dart
├── core/                  # Shared utilities, themes, constants
│   ├── network/
│   ├── utils/
│   └── widgets/           # Reusable app-wide widgets
└── features/
    ├── auth/              # Feature 1: Authentication
    │   ├── data/          # Models, repositories, data sources
    │   ├── domain/        # Entities, use cases
    │   └── presentation/  # Screens, widgets, state management (BLoC/Riverpod)
    └── products/          # Feature 2: Product Catalog
        ├── data/
        ├── domain/
        └── presentation/
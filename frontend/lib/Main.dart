import 'package:flutter/material.dart';
// import 'package:flutter/services.dart';
import 'package:shared_preferences/shared_preferences.dart';
import 'package:flutter_dotenv/flutter_dotenv.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:intl/date_symbol_data_local.dart';

import 'App.dart';
import 'core/providers/shared_preferences_provider.dart';

Future<void> main() async {
  // Memastikan binding Flutter sudah siap
  WidgetsFlutterBinding.ensureInitialized();

  // Load file .env
  await dotenv.load(fileName: ".env");

  // Load lokasi indonesia
   await initializeDateFormatting('id_ID', null);

  // Instansi SharePreferences
  final prefs = await SharedPreferences.getInstance();

  runApp(
    // For widgets to be able to read providers, we need to wrap the entire
    // application in a "ProviderScope" widget.
    // This is where the state of our providers will be stored.
    ProviderScope(
      overrides: [
        sharedPreferencesProvider.overrideWithValue(prefs)
      ],
      child: const MyApp(),
    ),
  );
}

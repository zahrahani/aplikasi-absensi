import 'dart:convert';
import 'package:flutter/material.dart';
import 'package:flutter_dotenv/flutter_dotenv.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:http/http.dart' as http;

import 'package:presensi/core/providers/shared_preferences_provider.dart';

class GuestScreen extends ConsumerStatefulWidget {
  final Widget child;

  const GuestScreen({
    super.key,
    required this.child,
  });

  @override
  ConsumerState<GuestScreen> createState() => _GuestScreenState();
}

class _GuestScreenState extends ConsumerState<GuestScreen> {
  bool _isLoading = true;

  @override
  void initState() {
    super.initState();
    _checkAuth();
  }

  Future<void> _checkAuth() async {
    final isAuthenticated = await _me(ref: ref);
    
    // pastikan widget masih ada
    if (!mounted) return; 

    if (!isAuthenticated) {
      // tampilkan child
      setState(() => _isLoading = false); 
    } else {
      // redirect ke main
      Navigator.pushReplacementNamed(context, '/main'); 
    }
  }

  @override
  Widget build(BuildContext context) {
    if (_isLoading) {
      return const Scaffold(
        body: Center(child: CircularProgressIndicator()),
      );
    }

    return widget.child;
  }

  Future<bool> _me({required WidgetRef ref}) async {
    final apiEndpoint = dotenv.env['API_ENDPOINT'];
    final prefs = ref.read(sharedPreferencesProvider);
    final userString = prefs.getString('user');

    if (userString == null) return false;

    final user = jsonDecode(userString);
    
    try {
      final Uri url = Uri.parse('$apiEndpoint/me');

      final response = await http.post(
        url,
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
        },
        body: jsonEncode({
          'user_id': user['user_id'],
          'remember_token': user['remember_token'],
        }),
      );

      
      return response.statusCode == 200;

    } catch (e) {
      return false;
    }
  }
}
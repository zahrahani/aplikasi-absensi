import 'dart:async';
import 'dart:convert';
import 'dart:io';
import 'package:flutter/material.dart';
import 'package:flutter_dotenv/flutter_dotenv.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:http/http.dart' as http;

import 'package:presensi/core/providers/shared_preferences_provider.dart';

class AuthScreen extends ConsumerStatefulWidget {
  final Widget child;

  const AuthScreen({
    super.key,
    required this.child,
  });

  @override
  ConsumerState<AuthScreen> createState() => _AuthScreenState();
}

class _AuthScreenState extends ConsumerState<AuthScreen> {
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

    if (isAuthenticated) {
      // tampilkan child
      setState(() => _isLoading = false); 
    } else {
      // redirect ke login
      Navigator.pushReplacementNamed(context, '/login'); 
    }
  }

  @override
  Widget build(BuildContext context) {
    if (_isLoading) {
      return const Scaffold(
        body: Center(child: CircularProgressIndicator()),
      );  
    }

    return SafeArea(child: widget.child);
  }

  Future<bool> _me({required WidgetRef ref}) async {
  final apiEndpoint = dotenv.env['API_ENDPOINT'];
  final prefs      = ref.read(sharedPreferencesProvider);
  final userString = prefs.getString('user');

  if (userString == null) return false;

  Map<String, dynamic> user;
  try {
    user = Map<String, dynamic>.from(jsonDecode(userString));
  } catch (e) {
    // Data di shared preferences rusak, hapus dan paksa login ulang
    await prefs.remove('user');
    return false;
  }

  try {
    final response = await http.post(
      Uri.parse('$apiEndpoint/me'),
      headers: {
        'Content-Type': 'application/json',
        'Accept'      : 'application/json',
      },
      body: jsonEncode({
        'user_id'       : user['user_id'],
        'remember_token': user['remember_token'],
      }),
    ).timeout(const Duration(seconds: 10)); // ← tambah timeout

    if (response.statusCode == 200) {
      final body = jsonDecode(response.body);

      if (body['data'] != null && body['data'] is Map) {
        await prefs.setString('user', jsonEncode(body['data']));
      }

      return true;
    }

    return false;

  } on TimeoutException {
    print('ME: Timeout');
    // Jika timeout, anggap masih login agar tidak paksa logout
    return true;
  } on SocketException {
    print('ME: No internet');
    // Jika tidak ada internet, anggap masih login
    return true;
  } catch (e) {
    print('ME Error: $e');
    return false;
  }
}
}
// lib/features/guest/controllers/login_controller.dart
import 'dart:convert';
import 'package:flutter_dotenv/flutter_dotenv.dart';
import 'package:http/http.dart' as http;

class LoginController {

  // Login Request
  static Future<Map<String, dynamic>> login({
    required String username,
    required String password,
  }) async {
    
    final apiEndpoint = dotenv.env['API_ENDPOINT'];

    try {
      // Api Endpoint /presensi/api/login
      final Uri url = Uri.parse(
        '$apiEndpoint/login'
      );

      // Request POST
      final response = await http.post(
        url,

        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
        },

        body: jsonEncode({
          'username': username,
          'password': password,
        }),
      );

      // Response success
      if (response.statusCode == 200) {

        final data = jsonDecode(response.body);
        

        return {
          'success': true,
          'data': data,
        };
      }

      // Response error
      return {
        'success': false,
        'errors_messages': jsonDecode(response.body)['errors_messages']
            ?? 'Login gagal',
      };

    } catch (e) {

      // Error Handling
      return {
        'success': false,
        'messages': e.toString(),
      };
    }
  }
}
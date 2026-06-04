import 'dart:convert';

import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:presensi/core/providers/shared_preferences_provider.dart';
import 'package:presensi/core/theme/app_colors.dart';
import 'package:presensi/core/theme/app_size.dart';
import 'package:presensi/features/presentation/guest/login/loginComponent.dart';
import 'package:presensi/features/presentation/guest/login/loginController.dart';
import 'package:presensi/features/presentation/guest/login/widgets_for_login/buildBlurCircle.dart';
import 'package:presensi/features/presentation/guest/login/widgets_for_login/buildWelcomeText.dart';
import 'package:presensi/features/widgets/companyBadge.dart';
import 'package:presensi/features/widgets/showAlert.dart';



class LoginScreen extends ConsumerStatefulWidget {
  const LoginScreen({super.key});

  @override
  ConsumerState<LoginScreen> createState() => _LoginScreenState();
}

class _LoginScreenState extends ConsumerState<LoginScreen> {
  final TextEditingController _usernameController = TextEditingController();
  final TextEditingController _passwordController = TextEditingController();
  final Map<String, String?> errors = {
    'username': null, 
    'password': null
  };

  bool _isPasswordVisible = false;
 
  Future<void> validateLogin() async {

    final result = await LoginController.login(
      username: _usernameController.text,
      password: _passwordController.text,
    );

    if (result['success']) {

        final prefs = ref.read(sharedPreferencesProvider);

        await prefs.setString(
          'user', jsonEncode(result['data'])
        );

        Navigator.pushReplacementNamed(
          context,
          '/main'
        );

        return;
    } else {

      final apiErrors = result['errors_messages'];

      if (apiErrors is String) {
          showAlert(
            context: context, 
            message: apiErrors, 
            alertStatus: "danger"
          );

          return;
        }

      setState(() {

        errors['username'] = apiErrors['username'];
        errors['password'] = apiErrors['password'];
        
      });
    }
  }

  @override
  void dispose() {
    _usernameController.dispose();
    _passwordController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      resizeToAvoidBottomInset: true,
      body: Container(
        decoration: const BoxDecoration(
          gradient: LinearGradient(
            begin: Alignment.topCenter,
            end: Alignment.bottomCenter,
            colors:AppColors.gradientBackground,
          ),
        ),
        child: Stack(
          children: [
            // Lingkaran blur
            Positioned(
              top: -80,
              left: -80,
              child: buildBlurCircle(AppSize.blurSize, AppColors.blurCircleColor),
            ),
            Positioned(
              bottom: 150,
              right: -50,
              child: buildBlurCircle(AppSize.blurSize, AppColors.blurCircleColor),
            ),

            SafeArea(
              child: LayoutBuilder(
                builder: (context, constraints) {
                  return SingleChildScrollView(
                    padding: EdgeInsets.only(
                      bottom: MediaQuery.of(context).viewInsets.bottom
                    ),

                    child: ConstrainedBox(
                      constraints: BoxConstraints(
                        minHeight: constraints.maxHeight
                      ),

                      child: IntrinsicHeight(
                        child: Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                        // =========================
                        // HEADER
                        // =========================
                        Padding(
                          padding: const EdgeInsets.only(
                            top: 24,
                            left: 24,
                            right: 24,
                          ),

                          child: buildCompanyBadge(),
                        ),

                        // =========================
                        // WELCOME TEXT
                        // =========================
                        const SizedBox(height: 60),

                        Padding(
                          padding: const EdgeInsets.symmetric(
                            horizontal: 24,
                          ),

                          child: buildWelcomeText(),
                        ),

                        const Spacer(),

                        // =========================
                        // LOGIN CARD
                        // =========================
                        buildLoginCard(
                          nipController: _usernameController,
                          passwordController: _passwordController,
                          errors: errors,
                          validateLogin: validateLogin,
                          isPasswordVisible: _isPasswordVisible,
                          onToggleVisibility: () {
                            setState(() {
                              _isPasswordVisible = !_isPasswordVisible;
                            });
                          }
                        ),
                          ],
                        ),
                      ),
                    ),
                  );
              }),
            )
          ],
        ),
      ),
    );
  }  
}
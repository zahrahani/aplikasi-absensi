// Ini merupakan Widget Card bagian card dari login
import 'package:flutter/material.dart';
import 'package:presensi/core/theme/app_colors.dart';
import 'package:presensi/features/widgets/InputField.dart';

Widget buildLoginCard({
  required TextEditingController nipController,
  required TextEditingController passwordController,
  required VoidCallback validateLogin,
  required bool isPasswordVisible,
  required VoidCallback onToggleVisibility,
  required final Map<String, String?> errors
}) {
    return Container(
      width: double.infinity,
      padding: EdgeInsets.only(
        top: 20,
        left: 28,
        right: 28,
      ),
      decoration: const BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.only(
          topLeft: Radius.circular(36),
          topRight: Radius.circular(36),
        ),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        mainAxisSize: MainAxisSize.min,
        children: [
          const Text(
            'Login Sekarang',
            style: TextStyle(
              fontFamily: 'Roboto',
              color: AppColors.primaryPurple,
              fontSize: 24,
              fontWeight: FontWeight.w900,
            ),
          ),
          const SizedBox(height: 4),
          const Text(
            'Isi data di bawah untuk melanjutkan',
            style: TextStyle(
              fontFamily: 'Roboto',
              color: Color(0xFF9CA3AF),
              fontSize: 14,
            ),
          ),
          const SizedBox(height: 24),
          buildUsernameField(
            usernameIcon: Icon(
              Icons.person_outline_rounded,
              color: Color(0xFF6B7280),
              size: 22,
            ),
            usernameController:  nipController,
            usernamePlaceholder: "Masukan Username",
            errorText: errors['username']
          ),
          const SizedBox(height: 14),
          buildPasswordField(
            passwordController: passwordController,
            passwordPlaceholder: 'Masukkan Password',
            isPasswordVisible: isPasswordVisible,
            onToggleVisibility: onToggleVisibility,
            errorText: errors['password']
          ),
          const SizedBox(height: 12),
          buildLoginButton(
            gradientColor: AppColors.gradientBackground,
            onPressed: validateLogin
          ),
          const SizedBox(height: 8),
        ],
      ),
    );
  }

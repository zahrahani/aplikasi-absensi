import 'dart:ui';
import 'package:flutter/material.dart';

class LoginPage extends StatefulWidget {
  const LoginPage({super.key});

  @override
  State<LoginPage> createState() => _LoginPageState();
}

class _LoginPageState extends State<LoginPage> {
  final TextEditingController _nipController = TextEditingController();
  final TextEditingController _passwordController = TextEditingController();

  bool _isPasswordVisible = false;
  bool _isRememberMe = false;

  static const Color _primaryPurple = Color(0xFF1900A7);

  static const String _validNIP = '12345';
  static const String _validPassword = 'admin123';

  // Fungsi validasi login
  void _validateLogin() {
    final String nip = _nipController.text.trim();
    final String password = _passwordController.text;

    if (nip.isEmpty) {
      _showError('NIP tidak boleh kosong');
      return;
    }

    if (password.isEmpty) {
      _showError('Password tidak boleh kosong');
      return;
    }

    if (password.length < 8) {
      _showError('Password minimal 8 karakter');
      return;
    }

    if (nip != _validNIP || password != _validPassword) {
      _showError('NIP atau Password salah');
      return;
    }
  }

  // Tampilkan pesan error dengan SnackBar
  void _showError(String message) {
    _nipController.clear();
    _passwordController.clear();

    ScaffoldMessenger.of(context).showSnackBar(
      SnackBar(
        content: Row(
          children: [
            const Icon(Icons.error_outline, color: Colors.white, size: 20),
            const SizedBox(width: 8),
            Text(
              message,
              style: const TextStyle(
                color: Colors.white,
                fontWeight: FontWeight.w500,
                fontFamily: 'Roboto',
              ),
            ),
          ],
        ),
        backgroundColor: const Color(0xFFE53E3E),
        behavior: SnackBarBehavior.floating,
        shape: RoundedRectangleBorder(
          borderRadius: BorderRadius.circular(12),
        ),
        margin: const EdgeInsets.all(16),
        duration: const Duration(seconds: 3),
      ),
    );
  }

  @override
  void dispose() {
    _nipController.dispose();
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
            colors: [
              Color(0xFF1900A7),
              Color(0xFF0D0060),
            ],
          ),
        ),
        child: Stack(
          children: [
            // Lingkaran blur
            Positioned(
              top: -80,
              left: -80,
              child: _buildBlurCircle(200, const Color(0xFF5B33FF)),
            ),
            Positioned(
              bottom: 150,
              right: -50,
              child: _buildBlurCircle(200, const Color(0xFF5B33FF)),
            ),

            SafeArea(
              child: Column(
                children: [
                  // Badge perusahaan
                  Padding(
                    padding: const EdgeInsets.only(
                      top: 24,
                      left: 24,
                      right: 24,
                    ),
                    child: Align(
                      alignment: Alignment.centerLeft,
                      child: _buildCompanyBadge(),
                    ),
                  ),

                  // Teks selamat datang
                  Expanded(
                    child: Align(
                      alignment: Alignment.centerLeft,
                      child: Padding(
                        padding: const EdgeInsets.symmetric(horizontal: 24),
                        child: _buildWelcomeText(),
                      ),
                    ),
                  ),

                  // Card login menempel di bawah
                  _buildLoginCard(),
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }

  // Widget: Lingkaran blur/glow
  Widget _buildBlurCircle(double size, Color color) {
    return Container(
      width: size,
      height: size,
      decoration: BoxDecoration(
        shape: BoxShape.circle,
        color: Colors.transparent,
        boxShadow: [
          BoxShadow(
            color: color.withValues(alpha: 0.7),
            blurRadius: 180,
            spreadRadius: 80,
          ),
        ],
      ),
    );
  }
  
  // Widget: Badge perusahaan
  Widget _buildCompanyBadge() {
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 2.5),
      decoration: BoxDecoration(
        color: Colors.white.withValues(alpha: 0.15),
        borderRadius: BorderRadius.circular(50),
        border: Border.all(
          color: Colors.white.withValues(alpha: 0.25),
          width: 1,
        ),
      ),
      child: Row(
        mainAxisSize: MainAxisSize.min,
        children: [
          Icon(
            Icons.business_rounded,
            color: Colors.white.withValues(alpha: 0.9),
            size: 15,
          ),
          const SizedBox(width: 7),
          Text(
            'CV. NAFIHAKA Creative',
            style: TextStyle(
              fontFamily: 'Roboto',
              color: Colors.white.withValues(alpha: 0.95),
              fontSize: 12,
              fontWeight: FontWeight.w500,
              letterSpacing: 0.3,
            ),
          ),
        ],
      ),
    );
  }

  // Widget: Teks welcome 
  Widget _buildWelcomeText() {
    return Column(
      mainAxisSize: MainAxisSize.min,
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        const Text(
          'Selamat Datang.',
          style: TextStyle(
            fontFamily: 'Roboto',
            color: Colors.white,
            fontSize: 38,
            fontWeight: FontWeight.w900,
            height: 1.1,
          ),
        ),
        const SizedBox(height: 12),
        RichText(
          text: TextSpan(
            style: TextStyle(
              fontFamily: 'Roboto',
              color: Colors.white.withValues(alpha: 0.72),
              fontSize: 15,
              height: 1.5,
            ),
            children: const [
              TextSpan(text: 'Catat kehadiran hanya dengan '),
              TextSpan(
                text: 'sekali pindai',
                style: TextStyle(
                  fontWeight: FontWeight.w700,
                  color: Colors.white,
                ),
              ),
            ],
          ),
        ),
      ],
    );
  }

  // Widget: Card login
  Widget _buildLoginCard() {
    return Container(
      width: double.infinity,
      padding: EdgeInsets.only(
        top: 20,
        left: 28,
        right: 28,
        bottom: MediaQuery.of(context).viewInsets.bottom + 28,
      ),
      decoration: const BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.only(
          topLeft: Radius.circular(36),
          topRight: Radius.circular(36),
        ),
      ),
      child: SingleChildScrollView(
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          mainAxisSize: MainAxisSize.min,
          children: [
            const Text(
              'Login Sekarang',
              style: TextStyle(
                fontFamily: 'Roboto',
                color: _primaryPurple,
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
            _buildNIPField(),
            const SizedBox(height: 14),
            _buildPasswordField(),
            const SizedBox(height: 12),
            _buildRememberMe(),
            const SizedBox(height: 20),
            _buildLoginButton(),
            const SizedBox(height: 8),
          ],
        ),
      ),
    );
  }

  // Widget: TextField NIP 
  Widget _buildNIPField() {
    return TextField(
      controller: _nipController,
      keyboardType: TextInputType.number,
      style: const TextStyle(
        fontFamily: 'Roboto',
        fontSize: 15,
        color: Color(0xFF1F2937),
      ),
      decoration: InputDecoration(
        hintText: 'Masukkan NIP',
        hintStyle: const TextStyle(
          fontFamily: 'Roboto',
          color: Color(0xFF9CA3AF),
          fontSize: 15,
        ),
        prefixIcon: const Icon(
          Icons.person_outline_rounded,
          color: Color(0xFF6B7280),
          size: 22,
        ),
        filled: true,
        fillColor: const Color(0xFFF3F4F6),
        border: OutlineInputBorder(
          borderRadius: BorderRadius.circular(14),
          borderSide: BorderSide.none,
        ),
        enabledBorder: OutlineInputBorder(
          borderRadius: BorderRadius.circular(14),
          borderSide: BorderSide.none,
        ),
        focusedBorder: OutlineInputBorder(
          borderRadius: BorderRadius.circular(14),
          borderSide: const BorderSide(color: _primaryPurple, width: 1.5),
        ),
        contentPadding: const EdgeInsets.symmetric(
          horizontal: 16,
          vertical: 16,
        ),
      ),
    );
  }

  // Widget: TextField Password 
  Widget _buildPasswordField() {
    return TextField(
      controller: _passwordController,
      obscureText: !_isPasswordVisible,
      style: const TextStyle(
        fontFamily: 'Roboto',
        fontSize: 15,
        color: Color(0xFF1F2937),
      ),
      decoration: InputDecoration(
        hintText: 'Masukkan Password',
        hintStyle: const TextStyle(
          fontFamily: 'Roboto',
          color: Color(0xFF9CA3AF),
          fontSize: 15,
        ),
        prefixIcon: const Icon(
          Icons.lock_outline_rounded,
          color: Color(0xFF6B7280),
          size: 22,
        ),
        suffixIcon: IconButton(
          icon: Icon(
            _isPasswordVisible
                ? Icons.visibility_outlined
                : Icons.visibility_off_outlined,
            color: const Color(0xFF6B7280),
            size: 22,
          ),
          onPressed: () {
            setState(() {
              _isPasswordVisible = !_isPasswordVisible;
            });
          },
        ),
        filled: true,
        fillColor: const Color(0xFFF3F4F6),
        border: OutlineInputBorder(
          borderRadius: BorderRadius.circular(14),
          borderSide: BorderSide.none,
        ),
        enabledBorder: OutlineInputBorder(
          borderRadius: BorderRadius.circular(14),
          borderSide: BorderSide.none,
        ),
        focusedBorder: OutlineInputBorder(
          borderRadius: BorderRadius.circular(14),
          borderSide: const BorderSide(color: _primaryPurple, width: 1.5),
        ),
        contentPadding: const EdgeInsets.symmetric(
          horizontal: 16,
          vertical: 16,
        ),
      ),
    );
  }

  // Widget: Checkbox Ingat Saya
  Widget _buildRememberMe() {
    return Row(
      children: [
        SizedBox(
          width: 24,
          height: 24,
          child: Checkbox(
            value: _isRememberMe,
            onChanged: (bool? value) {
              setState(() {
                _isRememberMe = value ?? false;
              });
            },
            activeColor: _primaryPurple,
            shape: RoundedRectangleBorder(
              borderRadius: BorderRadius.circular(4),
            ),
            side: const BorderSide(color: Color(0xFFD1D5DB), width: 1.5),
          ),
        ),
        const SizedBox(width: 10),
        const Text(
          'Ingat Saya',
          style: TextStyle(
            fontFamily: 'Roboto',
            color: Color(0xFF6B7280),
            fontSize: 14,
          ),
        ),
      ],
    );
  }

  // Widget: Tombol Masuk 
  Widget _buildLoginButton() {
    return SizedBox(
      width: double.infinity,
      height: 54,
      child: DecoratedBox(
        decoration: BoxDecoration(
          gradient: const LinearGradient(
            colors: [
              Color(0xFF3A1FCC),
              _primaryPurple,
            ],
          ),
          borderRadius: BorderRadius.circular(14),
          boxShadow: [
            BoxShadow(
              color: _primaryPurple.withValues(alpha: 0.4),
              blurRadius: 14,
              offset: const Offset(0, 5),
            ),
          ],
        ),
        child: ElevatedButton(
          onPressed: _validateLogin,
          style: ElevatedButton.styleFrom(
            backgroundColor: Colors.transparent,
            shadowColor: Colors.transparent,
            shape: RoundedRectangleBorder(
              borderRadius: BorderRadius.circular(14),
            ),
          ),
          child: const Text(
            'Masuk',
            style: TextStyle(
              fontFamily: 'Roboto',
              color: Colors.white,
              fontSize: 16,
              fontWeight: FontWeight.w700,
              letterSpacing: 0.5,
            ),
          ),
        ),
      ),
    );
  }
}
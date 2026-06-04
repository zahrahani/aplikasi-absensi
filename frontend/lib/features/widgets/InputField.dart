import 'package:flutter/material.dart';
import 'package:presensi/core/theme/app_colors.dart';
import 'package:presensi/core/theme/app_font.dart';
// Function untuk setel bulan
String monthName(int m) {
      const names = [
        '',
        'Jan','Feb','Mar','Apr','Mei','Jun',
        'Jul','Agu','Sep','Okt','Nov','Des'
      ];
      return names[m];
}



// Widget: TextField NIP
Widget buildUsernameField({
  required Widget usernameIcon,
  required TextEditingController usernameController,
  required String usernamePlaceholder,
  required String? errorText,
  }) {
return TextField(
  controller: usernameController,
  keyboardType: TextInputType.name,
  style: TextStyle(
    fontFamily: AppFont.fontDefault,
    fontSize: 15,
    color: Color(0xFF1F2937),
  ),
  decoration: InputDecoration(
    hintText: usernamePlaceholder,
    errorText: errorText,
    hintStyle: TextStyle(
      fontFamily: AppFont.fontDefault,
      color: Color(0xFF9CA3AF),
      fontSize: 15,
    ),
    prefixIcon: Icon(
      Icons.person_outline_rounded,
      color: Color(0xFF6B7280),
      size: 22,
    ),
    filled: true,
    fillColor: Color(0xFFF3F4F6),
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
      borderSide: BorderSide(color: AppColors.primaryPurple, width: 1.5),
    ),
    contentPadding: EdgeInsets.symmetric(
      horizontal: 16,
      vertical: 16,
    ),
    errorBorder: OutlineInputBorder(
      borderRadius: BorderRadius.circular(14),
      borderSide: const BorderSide(
        color: Colors.red,
        width: 1.5,
      ),
    ),
    focusedErrorBorder: OutlineInputBorder(
      borderRadius: BorderRadius.circular(14),
      borderSide: const BorderSide(
        color: Colors.red,
        width: 2,
      ),
    )
  ),
);
}


// Widget: TextField Password
Widget buildPasswordField({
  required TextEditingController passwordController,
  required String passwordPlaceholder,
  required bool isPasswordVisible,
  required VoidCallback onToggleVisibility,
  required String? errorText
}) {
  
  return TextField(
    controller: passwordController,
    obscureText: !isPasswordVisible,
    style: TextStyle(
      fontFamily: AppFont.fontDefault,
      fontSize: 15,
      color: Color(0xFF1F2937),
    ),
    decoration: InputDecoration(
      hintText: 'Masukkan Password',
      hintStyle: TextStyle(
        fontFamily: AppFont.fontDefault,
        color: Color(0xFF9CA3AF),
        fontSize: 15,
      ),
      errorText: errorText,
      prefixIcon: Icon(
        Icons.lock_outline_rounded,
        color: Color(0xFF6B7280),
        size: 22,
      ),
      suffixIcon: IconButton(
        icon: Icon(
          isPasswordVisible
              ? Icons.visibility_outlined
              : Icons.visibility_off_outlined,
          color: Color(0xFF6B7280),
          size: 22,
        ),
        onPressed: onToggleVisibility,
      ),
      filled: true,
      fillColor: Color(0xFFF3F4F6),
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
        borderSide: BorderSide(color: AppColors.primaryPurple, width: 1.5),
      ),
      errorBorder: OutlineInputBorder(
        borderRadius: BorderRadius.circular(14),
        borderSide: const BorderSide(
          color: Colors.red,
          width: 1.5,
        ),
      ),
      focusedErrorBorder: OutlineInputBorder(
        borderRadius: BorderRadius.circular(14),
        borderSide: const BorderSide(
          color: Colors.red,
          width: 2,
        ),
      ),
      contentPadding: EdgeInsets.symmetric(
        horizontal: 16,
        vertical: 16,
      ),
    ),
  );
}


  // Widget: Tombol Masuk
  Widget buildLoginButton({
    required List<Color> gradientColor,
    required VoidCallback onPressed,
  }) {
    return SizedBox(
      width: double.infinity,
      height: 54,
      child: DecoratedBox(
        decoration: BoxDecoration(
          gradient: LinearGradient(
            colors: gradientColor,
          ),
          borderRadius: BorderRadius.circular(14),
          boxShadow: [
            BoxShadow(
              color: AppColors.primaryPurple.withOpacity(0.4),
              blurRadius: 14,
              offset: const Offset(0, 5),
            ),
          ],
        ),
        child: ElevatedButton(
          onPressed: onPressed,
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

// Widget: Label dari input tanggal
Widget fieldLabel(String text) {
    return Text(text,
        style: const TextStyle(
            fontSize: 13,
            fontWeight: FontWeight.w600,
            color: Color(0xFF1A1A2E)
          ),
        );
      
  }


// Widget: pilih tanggal
Future<void> pickDate(TextEditingController ctrl, BuildContext context) async {
    final picked = await showDatePicker(
      context: context,
      initialDate: DateTime.now(),
      firstDate: DateTime(2025),
      lastDate: DateTime(2027),
      builder: (ctx, child) => Theme(
        data: ThemeData(
            colorScheme:
                const ColorScheme.light(primary: AppColors.primaryPurple)),
        child: child!,
      ),
    );
    if (picked != null) {

      ctrl.text =
    '${picked.day.toString().padLeft(2, '0')}-'
    '${picked.month.toString().padLeft(2, '0')}-'
    '${picked.year}';
    }
  }
// Widget: Input Tanggal
Widget dateField({
    required TextEditingController ctrl, 
    required String hint, 
    required String label,
    required BuildContext context,
}) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        fieldLabel(label),
        const SizedBox(height: 8),
        GestureDetector(
          onTap: () => pickDate(ctrl, context),
          child: AbsorbPointer(
            child: TextField(
              controller: ctrl,
              decoration: InputDecoration(
                hintText: hint,
                hintStyle: const TextStyle(
                    color: Color(0xFFAAAAAA), fontSize: 13),
                filled: true,
                fillColor: AppColors.whiteBackground,
                prefixIcon: const Icon(
                    Icons.calendar_today_outlined,
                    size: 18,
                    color: AppColors.primaryPurple),
                border: OutlineInputBorder(
                    borderRadius: BorderRadius.circular(12),
                    borderSide: const BorderSide(
                        color: Color(0xFFE0E0E0))),
                enabledBorder: OutlineInputBorder(
                    borderRadius: BorderRadius.circular(12),
                    borderSide: const BorderSide(
                        color: Color(0xFFE0E0E0))),
                focusedBorder: OutlineInputBorder(
                    borderRadius: BorderRadius.circular(12),
                    borderSide: const BorderSide(
                        color: AppColors.primaryPurple, width: 1.5)),
                contentPadding: const EdgeInsets.symmetric(
                    vertical: 14, horizontal: 14),
              ),
            ),
          ),
        ),
      ],
    );
  }
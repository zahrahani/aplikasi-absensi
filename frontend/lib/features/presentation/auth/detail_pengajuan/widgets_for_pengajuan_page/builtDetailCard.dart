import 'package:flutter/material.dart';

class builtDetailCard extends StatelessWidget {
  final List<Widget> children;
  const builtDetailCard({required this.children});
  @override
  Widget build(BuildContext context) {
    return Container(
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(14),
        boxShadow: [
          BoxShadow(
              color: Colors.black.withOpacity(0.04), blurRadius: 8)
        ],
      ),
      child: Column(children: children),
    );
  }
}
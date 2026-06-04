import 'package:flutter/material.dart';
import 'package:intl/intl.dart';
import 'dart:async';


class DayWidget extends StatefulWidget {
  const DayWidget({super.key});

  @override
  State<DayWidget> createState() => _DayWidgetState();
}

class _DayWidgetState extends State<DayWidget> {

  DateTime now = DateTime.now();

  @override
  void initState() {
    super.initState();

    Timer.periodic(
      const Duration(seconds: 1),
      (timer) {

        setState(() {
          now = DateTime.now();
        });

      },
    );
  }

  @override
  Widget build(BuildContext context) {

    final formatted = DateFormat(
      'EEEE, dd MMMM yyyy',
      'id_ID',
    ).format(now);

    return Text(
      formatted,
      style: TextStyle(
        fontSize: 12,
        color: Colors.grey[500],
      )
    );
  }
}

class ClockWidget extends StatefulWidget {
  const ClockWidget({super.key});

  @override
  State<ClockWidget> createState() => _ClockWidgetState();
}

class _ClockWidgetState extends State<ClockWidget> {

  DateTime now = DateTime.now();

  @override
  void initState() {
    super.initState();

    Timer.periodic(
      const Duration(seconds: 1),
      (timer) {

        setState(() {
          now = DateTime.now();
        });

      },
    );
  }

  @override
  Widget build(BuildContext context) {

    final formatted = DateFormat(
      'HH:mm',
      'id_ID',
    ).format(now);

    return Text(
      formatted,
      style: TextStyle(
        fontSize: 32,
        fontWeight: FontWeight.bold,
        color: Color(0xFF1A1A2E),
        height: 1.1,
      ));
  }
}
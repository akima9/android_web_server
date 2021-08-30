# 금연인 (PHP 부분)

**하루에 하나씩 줄여가는 금연 보조 안드로이드 애플리케이션입니다.**

**[ 개발환경 ]**

**· 개발인원 :** 1명

**· 인프라 :** AWS EC2(Ubuntu), AWS RDS

**· 개발언어 :** PHP

**· 웹서버 :** Apache

**· 데이터베이스 :** MySql

**· 상세내용**

1. 전체적인 UX/UI : BottomNavigationView & Fragment를 사용하여 메뉴와 3개의 화면(홈, 통계, 계정)을 구현하였습니다. CalendarView를 사용하여 달력 UI 구현하였습니다.

2. 자동로그인 : sharedpreferences를 사용하여 자동로그인 구현하였습니다.

3. 하루 단위 정보 저장 : Linux crontab을 사용하여 DB에 사용자의 일별 목표개수와 흡연횟수를 저장하였습니다.

**· PDF파일 URL :** https://drive.google.com/file/d/1BCILnI4-02ov60AlStQbFaQx-rBeK6ym/view

**· 안드로이드 부분 github URL :** https://github.com/akima9/Habbit2

**· 구글플레이 URL :** https://play.google.com/store/apps/details?id=com.kgy.habbit2 (AWS 프리 티어 기간 만료로 서버는 꺼놓은 상태입니다.)

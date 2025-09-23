# Two-Tier Examination System - Features Documentation

## Overview
The Two-Tier Examination System is a comprehensive Laravel-based web application designed for conducting educational assessments using a two-tier diagnostic testing approach. This system allows educators to create, manage, and monitor exams while providing students with a structured examination experience.

## Core Features

### 1. User Management System
- **Role-Based Access Control**:
  - Administrator (admin)
  - Teacher (guru)
  - Student (no login required for exams)
- **User Authentication**:
  - Login/logout functionality
  - Password management
  - User activation/deactivation
- **User Administration** (Admin only):
  - Create, edit, and delete users
  - Assign roles (admin/guru)
  - Manage user status

### 2. Question Bank Management
- **Two-Tier Question Structure**:
  - Tier 1: Conceptual understanding questions
  - Tier 2: Reasoning and justification questions
- **Question Components**:
  - Multiple-choice options (5 options per tier)
  - Difficulty levels (mudah/sedang/sulit - easy/medium/hard)
  - Chapter and subject categorization
  - Correct answer specification for both tiers
- **Question Management**:
  - Create, edit, and delete questions
  - Activate/deactivate questions
  - Filter questions by subject, chapter, difficulty
  - Search functionality

### 3. Exam Management System
- **Exam Creation**:
  - Title and description
  - Subject and chapter association
  - Grade and semester specification
  - Duration setting (in minutes)
  - Question selection from bank
  - Shuffle questions option
  - Immediate result display setting
- **Exam Status Management**:
  - Draft: Exam preparation phase
  - Waiting: Exam available for student entry
  - Active: Exam in progress
  - Finished: Exam completed
- **Exam Duplication**: Copy existing exams with new codes
- **Exam Deletion**: Remove draft or finished exams

### 4. Student Examination Process
- **Exam Access**:
  - Join exam using 6-character code
  - No account required for students
- **Waiting Room Process**:
  - Identity submission (name and identifier)
  - Approval waiting period for non-waiting exams
  - Ready-to-start status for approved participants
- **Examination Interface**:
  - Timed exam environment
  - Progress tracking
  - Two-tier question answering
  - Answer submission and modification
  - Automatic time management
- **Exam Completion**:
  - Manual finish option
  - Automatic timeout handling
  - Result calculation and display

### 5. Two-Tier Scoring System
- **Scoring Categories**:
  - Benar-Benar (Correct-Correct): 100% - Both concept and reasoning correct
  - Benar-Salah (Correct-Wrong): 50% - Concept correct but reasoning wrong
  - Salah-Benar (Wrong-Correct): 25% - Concept wrong but reasoning correct
  - Salah-Salah (Wrong-Wrong): 0% - Both concept and reasoning wrong
- **Point Calculation**:
  - Customizable points per question
  - Automatic score computation
  - Detailed breakdown of answer categories

### 6. Real-Time Monitoring & Control (Teacher Dashboard)
- **Live Exam Monitoring**:
  - Participant list with status tracking
  - Progress indicators
  - Current scores display
  - Time extension capabilities
- **Participant Management**:
  - Approve/deny student participation
  - Kick participants from exam
  - Extend individual or all participant time
- **Communication Tools**:
  - Broadcast messages to all participants
- **Exam Control**:
  - Start exam for all participants
  - End exam prematurely
  - Generate current results export

### 7. Results & Analytics
- **Individual Results**:
  - Detailed score breakdown
  - Answer analysis (correct vs. selected)
  - Time spent metrics
  - Two-tier category distribution
- **Class-Level Analytics**:
  - Average, highest, and lowest scores
  - Pass rate calculation
  - Score distribution charts
  - Question difficulty analysis
- **Export Capabilities**:
  - Current results export (CSV)
  - Final results export (CSV)
  - Detailed answer analysis

### 8. Subject & Curriculum Management (Admin)
- **Subject Management**:
  - Create and manage subjects
  - Subject code and description
- **Chapter Management**:
  - Chapter organization by subject
  - Grade and semester categorization
  - Chapter ordering

### 9. Technical Features
- **Responsive Design**: Mobile-friendly interface
- **Timezone Support**: Handle different timezones for distributed exams
- **Device Information Tracking**: Browser and platform detection
- **Automatic Code Generation**: Unique exam codes
- **Automatic Cleanup**: Session management and cleanup
- **Security Features**:
  - Role-based access control
  - Session validation
  - Input validation and sanitization

## User Roles & Permissions

### Administrator
- Full access to all system features
- User management
- Question bank management
- Subject and chapter management
- System-wide analytics

### Teacher (Guru)
- Exam creation and management
- Question bank access
- Exam monitoring and control
- Results analysis and export
- Student management during exams

### Student
- Exam participation
- Identity submission
- Question answering
- Result viewing (based on exam settings)

## Technical Architecture
- **Framework**: Laravel PHP Framework
- **Database**: Eloquent ORM models
- **Frontend**: Blade templates with JavaScript enhancements
- **Authentication**: Laravel's built-in authentication
- **Real-time Features**: AJAX-based polling for updates
- **Data Export**: CSV generation for results

## Key Models
1. **User**: System users (admin, guru)
2. **Exam**: Examination instances
3. **Question**: Two-tier questions
4. **StudentExamSession**: Student participation records
5. **StudentAnswer**: Student responses to questions
6. **Subject**: Academic subjects
7. **Chapter**: Subject chapters/units

This comprehensive system provides educators with powerful tools for creating diagnostic assessments that not only test factual knowledge but also probe deeper conceptual understanding and reasoning abilities.
<?php
/* $Id$ */

/* Translated by Kyriakos Xagoraris <theremon at users.sourceforge.net> */

$charset = 'utf-8';
$allow_recoding = TRUE;
$text_dir = 'ltr';
$left_font_family = 'verdana, arial, helvetica, geneva, sans-serif';
$right_font_family = 'tahoma, verdana, helvetica, geneva, sans-serif';
$number_thousands_separator = '.';
$number_decimal_separator = ',';
// shortcuts for Byte, Kilo, Mega, Giga, Tera, Peta, Exa
$byteUnits = array('Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB');

$day_of_week = array('Κυρ', 'Δευ', 'Τρι', 'Τετ', 'Πεμ', 'Παρ', 'Σαβ');
$month = array('Ιαν', 'Φεβ', 'Μάρ', 'Απρ', 'Μάι', 'Ιούν', 'Ιούλ', 'Αυγ', 'Σεπ', 'Οκτ', 'Νοε', 'Δεκ');
// See http://www.php.net/manual/en/function.strftime.php to define the
// variable below
$datefmt = '%d %B %Y, στις %I:%M %p';

// To Arrange

$strAccessDenied = '\'Αρνηση Πρόσβασης';
$strAction = 'Ενέργεια';
$strAddDeleteColumn = 'Προσθήκη/Αφαίρεση Στήλης Πεδίου';
$strAddDeleteRow = 'Προσθήκη/Αφαίρεση Γραμμής Κριτηρίων';
$strAddNewField = 'Προσθήκη νέου Πεδίου';
$strAddPriv = 'Προσθήκη νέου Προνομίου';
$strAddPrivMessage = 'Προσθέσατε νέο Προνόμιο.';
$strAddSearchConditions = 'Προσθήκη νέου όρου (σώμα της "where" πρότασης):';
$strAddToIndex = 'Προσθήκη στο ευρετήριο &nbsp;%s&nbsp;κολώνας(ων)';
$strAddUser = 'Προσθήκη νέου Χρήστη';
$strAddUserMessage = 'Προσθέσατε ένα νέο χρήστη.';
$strAffectedRows = 'Επηρεαζόμενες εγγραφές:';
$strAfter = 'Μετά το %s';
$strAfterInsertBack = 'Επιστροφή';
$strAfterInsertNewInsert = 'Εισαγωγή νέας εγγραφής';
$strAll = 'Όλα';
$strAllTableSameWidth = 'εμφάνιση όλων των πινάκων με το ίδιο πλάτος;';
$strAlterOrderBy = 'Αλλαγή ταξινόμησης Πίνακα κατά';
$strAnalyzeTable = 'Ανάλυση Πίνακα';
$strAnd = 'Και';
$strAnIndex = 'Ένα ευρετήριο προστέθηκε στο %s';
$strAny = 'Οποιοδήποτε';
$strAnyColumn = 'Οποιαδήποτε Στήλη';
$strAnyDatabase = 'Οποιαδήποτε Βάση';
$strAnyHost = 'Οποιοδήποτε Σύστημα';
$strAnyTable = 'Οποιοσδήποτε Πίνακας';
$strAnyUser = 'Οποιοσδήποτε Χρήστης';
$strAPrimaryKey = 'Ένα πρωτεύον κλειδί προστέθηκε στο %s';
$strAscending = 'Αύξουσα';
$strAtBeginningOfTable = 'Στην αρχή του Πίνακα';
$strAtEndOfTable = 'Στο τέλος του Πίνακα';
$strAttr = 'Χαρακτηριστικά';

$strBack = 'Επιστροφή';
$strBinary = 'Δυαδικό';
$strBinaryDoNotEdit = 'Δυαδικό - χωρίς δυνατότητα επεξεργασίας';
$strBookmarkDeleted = 'Η ετικέτα διεγράφη.';
$strBookmarkLabel = 'Ετικέτα';
$strBookmarkQuery = 'Αποθηκευμένη εντολή SQL';
$strBookmarkThis = 'Αποθήκευσε αυτήν την εντολή SQL';
$strBookmarkView = 'Μόνο ανάγνωση';
$strBrowse = 'Περιήγηση';
$strBzip = 'συμπίεση «bzip»';

$strCantLoadMySQL = 'δεν μπορεί να φορτωθεί η επέκταση MySQL,<br />παρακαλώ ελέγξτε τις ρυθμίσεις της PHP.';
$strCantRenameIdxToPrimary = 'Η μετονομασία του ευρετηρίου σε PRIMARY σε είναι εφικτή!';
$strCardinality = 'Μοναδικότητα';
$strCarriage = 'Χαρακτήρας επιστροφής: \\r';
$strChange = 'Αλλαγή';
$strChangePassword = 'Αλλαγή κωδικού πρόσβασης';
$strCheckAll = 'Επιλογή όλων';
$strCheckDbPriv = 'Έλεγχος προνομίων Βάσης';
$strCheckTable = 'Έλεγχος πίνακα';
$strColComFeat = 'Εμφάνιση σχολίων πεδίων';
$strColumn = 'Στήλη';
$strColumnNames = 'Ονόματα στηλών';
$strCompleteInserts = 'Ολοκληρωμένες εντολές «Insert»';
$strConfirm = 'Πραγματικά θέλετε να το εκτελέσετε;';
$strCookiesRequired = 'Από αυτό το σημείο πρέπει να έχετε ενεργοποιημένα cookies.';
$strCopyTable = 'Αντιγραφή πίνακα σε (βάση<b>.</b>πίνακας):';
$strCopyTableOK = 'Ο Πίνακας %s αντιγράφηκε στο %s.';
$strCreate = 'Δημιουργία';
$strCreateIndex = 'Δημιουργία ευρετηρίου σε &nbsp;%s&nbsp;πεδία';
$strCreateIndexTopic = 'Δημιουργία νέου ευρετηρίου';
$strCreateNewDatabase = 'Δημιουργία νέας βάσης';
$strCreateNewTable = 'Δημιουργία νέου πίνακα στη βάση %s';
$strCreatePdfFeat = 'Δημιουργία αρχείων PDF';
$strCriteria = 'Κριτήρια';

$strData = 'Δεδομένα';
$strDatabase = 'Βάση ';
$strDatabaseHasBeenDropped = 'Η βάση δεδομένων %s διεγράφη.';
$strDatabases = 'Βάσεις';
$strDatabasesStats = 'Στατιστικά βάσης';
$strDatabaseWildcard = 'Βάση δεδομένων (επιτρέπονται wildcards):';
$strDataOnly = 'Μόνο τα δεδομένα';
$strDefault = 'Προκαθορισμένο';
$strDelete = 'Διαγραφή';
$strDeleted = 'Η Εγγραφή έχει διαγραφεί';
$strDeletedRows = 'Διαγραμμένες Εγγραφές:';
$strDeleteFailed = 'Η διαγραφή απέτυχε';
$strDeleteUserMessage = 'Διαγράψατε τον χρήστη %s.';
$strDescending = 'Φθίνουσα';
$strDisabled = 'Απενεργοποιημένο';
$strDisplay = 'Εμφάνιση';
$strDisplayFeat = 'Λειτουργίες εμφάνισης';
$strDisplayOrder = 'Σειρά εμφάνισης:';
$strDoAQuery = 'Εκτέλεσε μία «επερώτηση κατά παράδειγμα» (χαρακτήρας μπαλαντέρ "%")';
$strDocu = 'Τεκμηρίωση';
$strDoYouReally = 'Θέλετε να εκτελέσετε την εντολή';
$strDrop = 'Διαγραφή';
$strDropDB = 'Διαγραφή βάσης %s';
$strDropTable = 'Διαγραφή πίνακα';
$strDumpingData = '\'Αδειασμα δεδομένων του πίνακα';
$strDynamic = 'δυναμικά';

$strEdit = 'Επεξεργασία';
$strEditPrivileges = 'Επεξεργασία Προνομίων';
$strEffective = 'Αποτελεσματικός';
$strEmpty = '\'Αδειασμα';
$strEmptyResultSet = 'Η MySQL επέστρεψε ένα άδειο σύνολο αποτελεσμάτων (π.χ. καμμία εγγραφή).';
$strEnabled = 'Ενεργοποιημένο';
$strEnd = 'Τέλος';
$strEnglishPrivileges = ' Σημείωση: Τα ονόματα προνομίων της MySQL εκφράζονται στα Αγγλικά ';
$strError = 'λάθος';
$strExtendedInserts = 'Εκτεταμένες εντολές «Insert»';
$strExtra = 'Πρόσθετα';

$strField = 'Πεδίο';
$strFieldHasBeenDropped = 'Το πεδίο %s διεγράφη';
$strFields = 'Πεδία';
$strFieldsEmpty = ' Η απαρίθμηση των πεδίων είναι κενή! ';
$strFieldsEnclosedBy = 'Πεδία που περικλείονται σε';
$strFieldsEscapedBy = 'Τα πεδία χρησιμοποιούν το χαρακτήρα διαφυγής ';
$strFieldsTerminatedBy = 'Πεδία που τελειώνουν σε';
$strFixed = 'προκαθορισμένου μήκους';
$strFlushTable = 'Εκκαθάριση ("FLUSH") πίνακα';
$strFormat = 'Μορφοποίηση';
$strFormEmpty = 'Ελλειπής τιμή στο πεδίο !';
$strFullText = 'Πλήρη κείμενα';
$strFunction = 'Έλεγχος';

$strGeneralRelationFeat = 'Γενικές λειτουργίες συσχέτισης';
$strGenTime = 'Χρόνος δημιουργίας';
$strGo = 'Εκτέλεση';
$strGrants = 'Παραχωρήσεις';
$strGzip = 'συμπίεση «gzip»';

$strHasBeenAltered = 'έχει αλλαχθεί.';
$strHasBeenCreated = 'έχει δημιουργηθεί.';
$strHome = 'Κεντρική σελίδα';
$strHomepageOfficial = 'Επίσημη σελίδα του phpMyAdmin';
$strHomepageSourceforge = 'Σελίδα του Sourceforge για την απόκτηση του phpMyAdmin';
$strHost = 'Σύστημα';
$strHostEmpty = 'Το όνομα του Συστήματος είναι κενό!';

$strIdxFulltext = 'Πλήρες κείμενο';
$strIfYouWish = 'Αν ενδιαφέρεστε να φορτώσετε μόνο μερικές απο τις στήλες του πίνακα, καθορίστε μία λίστα πεδίων διαχωρισμένα με κόμμα.';
$strIgnore = 'Παράληψη';
$strIndex = 'Ευρετήριο';
$strIndexes = 'Ευρετήρια';
$strIndexHasBeenDropped = 'Το ευρετήριο %s διεγράφη';
$strIndexName = 'Όνομα ευρετηρίου&nbsp;:';
$strIndexType = 'Τύπος ευρετηρίου&nbsp;:';
$strInsert = 'Εισαγωγή';
$strInsertAsNewRow = 'Εισαγωγή ως νέα εγγραφές';
$strInsertedRows = 'Εισαγόμενες εγγραφές:';
$strInsertNewRow = 'Εισαγωγή νέας εγγραφής';
$strInsertTextfiles = 'Εισαγωγή αρχείου κειμένου στον πίνακα';
$strInstructions = 'Οδηγίες';
$strInUse = 'σε χρήση';
$strInvalidName = 'Η «%s» είναι δεσμευμένη λέξη, δεν μπορείτε να την χρησιμοποιήσετε ως όνομα για Βάση, Πίνακα ή Πεδίο.';

$strKeepPass = 'Διατήρηση κωδικού πρόσβασης';
$strKeyname = 'Όνομα κλειδιού';
$strKill = 'Τερματισμός';

$strLength = 'Μήκος';
$strLengthSet = 'Μήκος/Τιμές*';
$strLimitNumRows = 'Εγγραφές ανά σελίδα';
$strLineFeed = 'Χαρακτήρας προώθησης γραμμής: \\n';
$strLines = 'Γραμμές';
$strLinesTerminatedBy = 'Γραμμές που τελειώνουν σε';
$strLocationTextfile = 'Τοποθεσία του αρχείου κειμένου';
$strLogin = 'Σύνδεση';
$strLogout = 'Αποσύνδεση';
$strLogPassword = 'Κωδικός πρόσβασης:';
$strLogUsername = 'Όνομα χρήστη:';

$strModifications = 'Οι αλλαγές αποθηκεύτηκαν';
$strModify = 'Τροποποίηση';
$strModifyIndexTopic = 'Αλλαγή ενός ευρετηρίου';
$strMoveTable = 'Μεταφορά πίνακα σε (βάση<b>.</b>πίνακας):';
$strMoveTableOK = 'Ο πίνακας %s μεταφέρθηκε στο %s.';
$strMySQLReloaded = 'Η MySQL επαναφορτώθηκε.';
$strMySQLSaid = 'Η MySQL επέστρεψε το μύνημα: ';
$strMySQLServerProcess = 'Η MySQL %pma_s1% εκτελείται στον %pma_s2% ως %pma_s3%';
$strMySQLShowProcess = 'Εμφάνιση διεργασιών';
$strMySQLShowStatus = 'Εμφάνιση πληροφορών εκτέλεσης της MySQL';
$strMySQLShowVars = 'Εμφάνιση μεταβλητών της MySQL';

$strName = 'Όνομα';
$strNext = 'Επόμενο';
$strNo = 'Όχι';
$strNoDatabases = 'Δεν υπάρχουν βάσεις δεδομένων';
$strNoDropDatabases = 'Οι εντολές «DROP DATABASE» έχουν απενεργοποιηθεί.';
$strNoFrames = 'Το phpMyAdmin είναι πιο φιλικό με έναν browser <b>που υποστηρίζει frames</b>.';
$strNoIndex = 'Δεν ορίστηκε ευρετήριο!';
$strNoIndexPartsDefined = 'Δεν ορίστηκαν τα στοιχεία του ευρετηρίου!';
$strNoModification = 'Χωρίς αλλαγή';
$strNone = 'Κανένα';
$strNoPassword = 'Χωρίς Κωδικό Πρόσβασης';
$strNoPrivileges = 'Χωρίς Προνόμια';
$strNoQuery = 'Δεν υπάρχει εντολή SQL!';
$strNoRights = 'Δεν έχετε αρκετά δικαιώματα να είσαστε εδώ τώρα!';
$strNoTablesFound = 'Δεν βρέθηκαν Πίνακες στη βάση.';
$strNotNumber = 'Αυτό δεν είναι αριθμός!';
$strNotOK = 'ΛΑΘΟΣ';
$strNotValidNumber = ' δεν είναι υπαρκτός αριθμός Εγγραφής!';
$strNoUsersFound = 'Δεν βρέθηκαν χρήστες.';
$strNull = 'Κενό';

$strOftenQuotation = 'Συχνά εισαγωγικά. Το OPTIONALLY σημαίνει ότι μόνο τα πεδία char και varchar εμπεριέχονται με τον χαρακτήρα «περιστοιχίζεται από».';
$strOK = 'OK';
$strOptimizeTable = 'Βελτιστοποίηση Πίνακα';
$strOptionalControls = 'Προεραιτικό. Ρυθμίζει πώς να γίνεται η ανάγνωση και η εγγραφή ειδικών χαρακτήρων.';
$strOptionally = 'ΠΡΟΑΙΡΕΤΙΚΑ';
$strOr = 'Ή';
$strOverhead = 'Επιβάρυνση';

$strPartialText = 'Επιμέρους κείμενα';
$strPassword = 'Κωδικός Πρόσβασης';
$strPasswordEmpty = 'Ο Κωδικός Πρόσβασης είναι κενός!';
$strPasswordNotSame = 'Οι κωδικοί πρόσβασης δεν είναι ίδιοι!';
$strPdfNoTables = 'Δεν υπάρχουν πίνακες';
$strPHPVersion = 'Έκδοση PHP';
$strPmaDocumentation = 'Τεκμηρίωση phpMyAdmin';
$strPmaUriError = 'Η εντολή <tt>$cfg[\'PmaAbsoluteUri\']</tt> ΠΡΕΠΕΙ να οριστεί στο αρχείο προεπιλογών!';
$strPos1 = 'Αρχή';
$strPrevious = 'Προηγούμενο';
$strPrimary = 'Πρωτεύον';
$strPrimaryKey = 'Πρωτεύον κλειδί';
$strPrimaryKeyHasBeenDropped = 'Το πρωτεύον κλειδί διεγράφη';
$strPrimaryKeyName = 'Το όνομα του πρωτεύοντος κλειδιού πρέπει να είναι... PRIMARY!';
$strPrimaryKeyWarning = '("PRIMARY" <b>πρέπει</b> να είναι το όνομα του πρωτεύοντος κλειδιού και <b>μόνο αυτού</b> !)';
$strPrintView = 'Εμφάνιση για εκτύπωση';
$strPrivileges = 'Προνόμια';
$strProperties = 'Ιδιότητες';

$strQBE = 'Επερώτηση κατά παράδειγμα';
$strQBEDel = 'Διαγραφή';
$strQBEIns = 'Εισαγωγή';
$strQueryOnDb = 'Εντολή SQL στη βάση <b>%s</b>:';

$strRecords = 'Εγγραφές';
$strReferentialIntegrity = 'Έλεγχος ακεραιότητας σχέσεων:';
$strRelationNotWorking = 'Οι επιπρόσθετες λειτουργίες για εργασία με συσχετισμένους πίνακες έχουν απενεργοποιηθεί. Για να μάθετε γιατί, πατήστε %sεδώ%s.';
$strReloadFailed = 'Η επανεκκίνηση της MySQL απέτυχε.';
$strReloadMySQL = 'Επανεκκίνηση της MySQL';
$strRememberReload = 'Ενθύμιση της επανεκκίνησης του διακομιστή.';
$strRenameTable = 'Μετονομασία πίνακα σε';
$strRenameTableOK = 'Ο Πίνακας %s μετονομάσθηκε σε %s';
$strRepairTable = 'Επιδιόρθωση πίνακα';
$strReplace = 'Αντικατάσταση';
$strReplaceTable = 'Αντικατάσταση δεδομένων Πίνακα με το αρχείο';
$strReset = 'Επαναφορά';
$strReType = 'Επαναεισαγωγή';
$strRevoke = 'Ανάκληση';
$strRevokeGrant = 'Ανάκληση Παραχώρισης';
$strRevokeGrantMessage = 'Ανακαλέσατε τα προνόμια Παραχώρισης του %s';
$strRevokeMessage = 'Ανακαλέσατε τα προνόμια για %s';
$strRevokePriv = 'Ανάκληση προνομοίων';
$strRowLength = 'Μέγεθος Γραμμής';
$strRows = 'Εγγραφές';
$strRowsFrom = 'Εγγραφές αρχίζοντας από την εγγραφή';
$strRowSize = ' Μέγεθος Εγγραφής ';
$strRowsModeHorizontal = 'οριζόντια';
$strRowsModeOptions = 'σε %s μορφή με επανάληψη επικεφαλίδων ανά %s κελιά';
$strRowsModeVertical = 'κάθετη';
$strRowsStatistic = 'Στατιστικά Εγγραφών';
$strRunning = 'που εκτελείται στο %s';
$strRunQuery = 'Υποβολή επερώτησης';
$strRunSQLQuery = 'Εκτέλεση εντολής/εντολών SQL στη βάση δεδομένων %s';

$strSave = 'Αποθήκευση';
$strSelect = 'Επιλογή';
$strSelectADb = 'Παρακαλώ επιλέξτε μία βάση δεδομένων';
$strSelectAll = 'Επιλογή όλων';
$strSelectFields = 'Επιλογή πεδίων (τουλάχιστον ένα)';
$strSelectNumRows = 'στην εντολή';
$strSend = 'Αποστολή';
$strServerChoice = 'Επιλογή Διακομιστή';
$strServerVersion = 'Έκδοση Διακομιστή';
$strSetEnumVal = 'Αν ο τύπος του πεδίου είναι «enum» ή «set», παρακαλώ εισάγετε τις τιμές χρησιμοποιώντας την εξής μορφοποίηση: \'α\',\'β\',\'γ\'...<br /> Αν χρειάζεται να εισάγετε την ανάποδη κάθετο ("\") ή απλά εισαγωγικά ("\'"), προθέστε τα με ανάποδη κάθετο στην αρχή (για παράδειγμα \'\\\\χψω\' ή \'α\\\'β\').';
$strShow = 'Εμφάνιση';
$strShowAll = 'Εμφάνιση όλων';
$strShowCols = 'Εμφάνιση στηλών';
$strShowingRecords = 'Εμφάνιση εγγραφής ';
$strShowPHPInfo = 'Εμφάνιση πληροφοριών της PHP';
$strShowTables = 'Εμφάνιση πινάκων';
$strShowThisQuery = ' Εμφάνισε εδώ ξανά αυτήν την εντολή ';
$strSingly = '(μοναδικά)';
$strSize = 'Μέγεθος';
$strSort = 'Ταξινόμιση';
$strSpaceUsage = 'Χρήση χώρου';
$strSQLQuery = 'Εντολή SQL';
$strStatement = 'Δηλώσεις';
$strStrucCSV = 'Δεδομένα CSV';
$strStrucData = 'Δομή και δεδομένα';
$strStrucDrop = 'Προσθήκη «Drop Table»';
$strStrucExcelCSV = 'Μορφή CSV για δεδομένα Ms Excel';
$strStrucOnly = 'Μόνο η δομή';
$strSubmit = 'Αποστολή';
$strSuccess = 'Η SQL εντολή σας εκτελέσθηκε επιτυχώς';
$strSum = 'Σύνολο';

$strTable = 'Πίνακας ';
$strTableComments = 'Σχόλια Πίνακα';
$strTableEmpty = 'Το όνομα του Πίνακα είναι κενό!';
$strTableHasBeenDropped = 'Ο Πίνακας %s διεγράφη';
$strTableHasBeenEmptied = 'Ο Πίνακας %s άδειασε';
$strTableHasBeenFlushed = 'Ο Πίνακας %s εκκαθαρίστικε ("FLUSH")';
$strTableMaintenance = 'Συντήρηση Πίνακα';
$strTables = '%s Πίνακας/Πίνακες';
$strTableStructure = 'Δομή Πίνακα για τον Πίνακα';
$strTableType = 'Τύπος Πίνακα';
$strTextAreaLength = ' Εξαιτίας του μεγέθος του,<br /> αυτό το πεδίο μπορεί να μη μπορεί να διορθωθεί ';
$strTheContent = 'Τα περιεχόμενα του αρχείου σας έχουν εισαγχθεί.';
$strTheContents = 'Τα περιεχόμενα του αρχείου αντικαθιστούν τα περιεχόμενα του επιλεγμένου πίνακα για Γραμμές με ίδιο πρωτεύον ή μοναδικό κλειδί.';
$strTheTerminator = 'Ο τερματικός χαρακτήρας των πεδίων.';
$strTotal = 'συνολικά';
$strType = 'Τύπος';

$strUncheckAll = 'Απεπιλογή όλων';
$strUnique = 'Μοναδικό';
$strUnselectAll = 'Απεπιλογή όλων';
$strUpdatePrivMessage = 'Τα προνόμια του χρήστη %s ενημερώθηκαν.';
$strUpdateProfile = 'Ενημέρωση στοιχείων:';
$strUpdateProfileMessage = 'Τα στοιχεία ανανεώθηκαν.';
$strUpdateQuery = 'Ενημέρωση της εντολής';
$strUsage = 'Χρήση';
$strUseBackquotes = 'Χρήση ανάποδων εισαγωγικών στα ονόματα των Πινάκων και των Πεδίων';
$strUser = 'Χρήστης';
$strUserEmpty = 'Το όνομα του χρήστη είναι κενό!';
$strUserName = 'Όνομα χρήστη';
$strUsers = 'Χρήστες';
$strUseTables = 'Χρήση Πινάκων';

$strValue = 'Τιμή';
$strViewDump = 'Εμφάνιση σχήματος του πίνακα';
$strViewDumpDB = 'Εμφάνιση σχήματος της βάσης';

$strWelcome = 'Καλωσήρθατε στο %s';
$strWithChecked = 'Με τους επιλεγμένους:';
$strWrongUser = 'Λανθασμένο όνομα χρήστη/κωδικός πρόσβασης. \'Αρνηση πρόσβασης.';

$strYes = 'Ναι';

$strZip = 'συμπίεση «zip»';
// To Translate

$strBeginCut = 'BEGIN CUT';  //to translate
$strBeginRaw = 'BEGIN RAW';  //to translate

$strCantLoadRecodeIconv = 'Δεν είναι δυνατή η φόρτωση της επέκτασης iconv ή recode που χρειάζεται για την μετατροπή του σετ χαρακτήρων. Ρυθμίστε την php να επιτρέπει την χρήση αυτών των επεκτάσεων ή απανεργοποιήστε την μετατροπή χαρακτήρων στο phpMyAdmin.';  //to translate
$strCantUseRecodeIconv = 'Δεν είναι δυνατή η χρήση της επέκτασης iconv ούτε της libiconv ούτε της ρουτίνας recode_string, ενώ η επέκταση έχει φορτωθεί. Ελέξτε τις ρυθμίσεις της php.';  //to translate
$strChangeDisplay = 'Επιλέξτε πεδίο για εμφάνιση';  //to translate
$strCharsetOfFile = 'Character set of the file:'; //to translate
$strChoosePage = 'Παρακαλώ επιλέξτε σελίδα για αλλαγή';  //to translate
$strComments = 'Σχόλια';  //to translate
$strConfigFileError = 'Το phpMyAdmin δεν μπόρεσε να διαβάσει το αρχείο ρυθμίσεων!<br />Αυτό μπορεί να συμβεί εάν η php βρει κάποιο λάθος στο αρχείο ή εάν η php δεν μπορεί να βρει το αρχείο.<br />Παρακαλώ καλέστε το αρχείο ρυθμίσεων απ\' ευθείας χρησιμοποιώντας το ακόλουθο link και διαβάστε τα μυνήματα λάθους που θα επιστρέψει η php. Στις περισσότερες περιπτώσεις κάπου λείπουν εισαγωγικά (") ή ερωτιματικά (;).<br />Εάν η php επιστρέψει μια λευκή σελίδα, όλα είναι σωστά.'; //to translate
$strConfigureTableCoord = 'Παρακαλώ ορίστε τις συντεταγμένες για τον πίνακα %s';  //to translate
$strCreatePage = 'Δημιουργία νέας σελίδας';  //to translate

$strDisplayPDF = 'Εμφάνιση σχήματος PDF';  //to translate
$strDumpXRows = 'Εμφάνιση %s εγγραφών ξεκινώντας από την εγγραφή %s.'; //to translate

$strEditPDFPages = 'Αλλαγή σελίδων PDF';  //to translate
$strEndCut = 'END CUT';  //to translate
$strEndRaw = 'END RAW';  //to translate
$strExplain = 'Explain SQL';  //to translate
$strExport = 'Εξαγωγή';  //to translate
$strExportToXML = 'Export to XML format'; //to translate

$strGenBy = 'Δημιουργήθηκε από:'; //to translate

$strHaveToShow = 'Πρέπει να επιλέξετε τουλάχιστον μία στήλη για εμφάνιση';  //to translate

$strLinkNotFound = 'Δεν βρέθηκε η σύνδεση';  //to translate
$strLinksTo = 'Σύνδεση με';  //to translate

$strMissingBracket = 'Λείπει μία αγκύλη';  //to translate
$strMySQLCharset = 'Σετ χαρακτήρων της MySQL';  //to translate

$strNoDescription = 'χωρίς περιγραφή';  //to translate
$strNoExplain = 'Skip Explain SQL';  //to translate
$strNoPhp = 'χωρίς κώδικα PHP';  //to translate
$strNotSet = 'Ο πίνακας <b>%s</b> δεν βρέθηκε ή δεν ορίστηκε στη %s';  //to translate
$strNoValidateSQL = 'Skip Validate SQL';  //to translate
$strNumSearchResultsInTable = '%s αποτελέσματα στον πίνακα <i>%s</i>';//to translate
$strNumSearchResultsTotal = '<b>Σύνολο:</b> <i>%s</i> αποτελέσματα';//to translate

$strOperations = 'Λειτουργίες';  //to translate
$strOptions = 'Επιλογές';  //to translate

$strPageNumber = 'Σελίδα:';  //to translate
$strPdfDbSchema = 'Σχήμα της βάσης "%s" - Σελίδα %s';  //to translate
$strPdfInvalidPageNum = 'Δεν ορίστηκε αριθμός σελίδας PDF!';  //to translate
$strPdfInvalidTblName = 'Ο πίνακας "%s" δεν υπάρχει!';  //to translate
$strPhp = 'Δημιουργία κώδικα PHP';  //to translate

$strRelationView = 'Εμφάνιση σχέσεων';  //to translate

$strScaleFactorSmall = 'Η κλίμακα είναι πολύ μικρή για να εμφανιστεί το σχήμα σε μία σελίδα';  //to translate
$strSearch = 'Αναζήτηση';//to translate
$strSearchFormTitle = 'Αναζήτηση στη βάση';//to translate
$strSearchInTables = 'Μέσα στους πίνακες:';//to translate
$strSearchNeedle = 'Όροι ή τιμές για αναζήτηση (μπαλαντέρ: "%"):';//to translate
$strSearchOption1 = 'τουλάχιστον έναν από τους όρους';//to translate
$strSearchOption2 = 'όλους τους όρους';//to translate
$strSearchOption3 = 'την ακριβή φράση';//to translate
$strSearchOption4 = 'ως regular expression';//to translate
$strSearchResultsFor = 'Αποτελέσματα αναζήτησης για "<i>%s</i>" %s:';//to translate
$strSearchType = 'Έυρεση:';//to translate
$strSelectTables = 'Επιλογή Πινάκων';  //to translate
$strShowColor = 'Εμφάνιση χρωμάτων';  //to translate
$strShowGrid = 'Εμφάνιση πλέγματος';  //to translate
$strShowTableDimension = 'Εμφάνιση διαστάσεων πινάκων';  //to translate
$strSplitWordsWithSpace = 'Οι λέξεις χωρίζονται από τον χαρακτήρα διαστήματος (" ").';//to translate
$strSQL = 'SQL'; //to translate
$strSQLParserBugMessage = 'There is a chance that you may have found a bug in the SQL parser. Please examine your query closely, and check that the quotes are correct and not mis-matched. Other possible failure causes may be that you are uploading a file with binary outside of a quoted text area. You can also try your query on the MySQL command line interface. The MySQL server error output below, if there is any, may also help you in diagnosing the problem. If you still have problems or if the parser fails where the command line interface succeeds, please reduce your SQL query input to the single query that causes problems, and submit a bug report with the data chunk in the CUT section below:';  //to translate
$strSQLParserUserError = 'There seems to be an error in your SQL query. The MySQL server error output below, if there is any, may also help you in diagnosing the problem';  //to translate
$strSQLResult = 'αποτέλεσμα SQL'; //to translate
$strSQPBugInvalidIdentifer = 'Invalid Identifer';  //to translate
$strSQPBugUnclosedQuote = 'Unclosed quote';  //to translate
$strSQPBugUnknownPunctuation = 'Unknown Punctuation String';  //to translate
$strStructPropose = 'Προτεινόμενη δομή πίνακα';  //to translate
$strStructure = 'Δομή';  //to translate

$strValidateSQL = 'Validate SQL';  //to translate

$strInsecureMySQL = 'Your configuration file contains settings (root with no password) that correspond to the default MySQL privileged account. Your MySQL server is running with this default, is open to intrusion, and you really should fix this security hole.';  //to translate
$strWebServerUploadDirectory = 'web-server upload directory';  //to translate
$strWebServerUploadDirectoryError = 'The directory you set for upload work cannot be reached';  //to translate
$strValidatorError = 'The SQL validator could not be initialized. Please check if you have installed the necessary php extensions as described in the %sdocumentation%s.'; //to translate
$strServer = 'Server %s';  //to translate
$strPutColNames = 'Put fields names at first row';  //to translate
$strImportDocSQL = 'Import docSQL Files';  //to translate
$strDataDict = 'Data Dictionary';  //to translate
$strPrint = 'Print';  //to translate
$strPHP40203 = 'You are using PHP 4.2.3, which has a serious bug with multi-byte strings (mbstring). See PHP bug report 19404. This version of PHP is not recommended for use with phpMyAdmin.';  //to translate
$strCompression = 'Compression'; //to translate
$strNumTables = 'Tables'; //to translate
$strTotalUC = 'Total'; //to translate
$strRelationalSchema = 'Relational schema';  //to translate
$strTableOfContents = 'Table of contents';  //to translate
$strCannotLogin = 'Cannot login to MySQL server';  //to translate
$strShowDatadictAs = 'Data Dictionary Format';  //to translate
$strLandscape = 'Landscape';  //to translate
$strPortrait = 'Portrait';  //to translate

$timespanfmt = '%s days, %s hours, %s minutes and %s seconds'; //to translate

$strAbortedClients = 'Aborted'; //to translate
$strConnections = 'Connections'; //to translate
$strFailedAttempts = 'Failed attempts'; //to translate
$strGlobalValue = 'Global value'; //to translate
$strMoreStatusVars = 'More status variables'; //to translate
$strPerHour = 'per hour'; //to translate
$strQueryStatistics = '<b>Query statistics</b>: Since its startup, %s queries have been sent to the server.';
$strQueryType = 'Query type'; //to translate
$strReceived = 'Received'; //to translate
$strSent = 'Sent'; //to translate
$strServerStatus = 'Runtime Information'; //to translate
$strServerStatusUptime = 'This MySQL server has been running for %s. It started up on %s.'; //to translate
$strServerTabVariables = 'Variables'; //to translate
$strServerTabProcesslist = 'Processes'; //to translate
$strServerTrafficNotes = '<b>Server traffic</b>: These tables show the network traffic statistics of this MySQL server since its startup.';
$strServerVars = 'Server variables and settings'; //to translate
$strSessionValue = 'Session value'; //to translate
$strTraffic = 'Traffic'; //to translate
$strVar = 'Variable'; //to translate

$strCommand = 'Command'; //to translate
$strCouldNotKill = 'phpMyAdmin was unable to kill thread %s. It probably has already been closed.'; //to translate
$strId = 'ID'; //to translate
$strProcesslist = 'Process list'; //to translate
$strStatus = 'Status'; //to translate
$strTime = 'Time'; //to translate
$strThreadSuccessfullyKilled = 'Thread %s was successfully killed.'; //to translate

$strBzError = 'phpMyAdmin was unable to compress the dump because of a broken Bz2 extension in this php version. It is strongly recommended to set the <code>$cfg[\'BZipDump\']</code> directive in your phpMyAdmin configuration file to <code>FALSE</code>. If you want to use the Bz2 compression features, you should upgrade to a later php version. See php bug report %s for details.'; //to translate
$strLaTeX = 'LaTeX';  //to translate

$strAdministration = 'Administration'; //to translate
$strFlushPrivilegesNote = 'Note: phpMyAdmin gets the users\' privileges directly from MySQL\'s privilege tables. The content of this tables may differ from the privileges the server uses if manual changes have made to it. In this case, you should %sreload the privileges%s before you continue.'; //to translate
$strGlobalPrivileges = 'Global privileges'; //to translate
$strGrantOption = 'Grant'; //to translate
$strPrivDescAllPrivileges = 'Includes all privileges except GRANT.'; //to translate
$strPrivDescAlter = 'Allows altering the structure of existing tables.'; //to translate
$strPrivDescCreateDb = 'Allows creating new databases and tables.'; //to translate
$strPrivDescCreateTbl = 'Allows creating new tables.'; //to translate
$strPrivDescCreateTmpTable = 'Allows creating temporary tables.'; //to translate
$strPrivDescDelete = 'Allows deleting data.'; //to translate
$strPrivDescDropDb = 'Allows dropping databases and tables.'; //to translate
$strPrivDescDropTbl = 'Allows dropping tables.'; //to translate
$strPrivDescExecute = 'Allows running stored procedures; Has no effect in this MySQL version.'; //to translate
$strPrivDescFile = 'Allows importing data from and exporting data into files.'; //to translate
$strPrivDescGrant = 'Allows adding users and privileges without reloading the privilege tables.'; //to translate
$strPrivDescIndex = 'Allows creating and dropping indexes.'; //to translate
$strPrivDescInsert = 'Allows inserting and replacing data.'; //to translate
$strPrivDescLockTables = 'Allows locking tables for the current thread.'; //to translate
$strPrivDescMaxConnections = 'Limits the number of new connections the user may open per hour.';
$strPrivDescMaxQuestions = 'Limits the number of queries the user may send to the server per hour.';
$strPrivDescMaxUpdates = 'Limits the number of commands that change any table or database the user may execute per hour.';
$strPrivDescProcess3 = 'Allows killing processes of other users.'; //to translate
$strPrivDescProcess4 = 'Allows viewing the complete queries in the process list.'; //to translate
$strPrivDescReferences = 'Has no effect in this MySQL version.'; //to translate
$strPrivDescReplClient = 'Gives the right to the user to ask where the slaves / masters are.'; //to translate
$strPrivDescReplSlave = 'Needed for the replication slaves.'; //to translate
$strPrivDescReload = 'Allows reloading server settings and flushing the server\'s caches.'; //to translate
$strPrivDescSelect = 'Allows reading data.'; //to translate
$strPrivDescShowDb = 'Gives access to the complete list of databases.'; //to translate
$strPrivDescShutdown = 'Allows shutting down the server.'; //to translate
$strPrivDescSuper = 'Allows connectiong, even if maximum number of connections is reached; Required for most administrative operations like setting global variables or killing threads of other users.'; //to translate
$strPrivDescUpdate = 'Allows changing data.'; //to translate
$strPrivDescUsage = 'No privileges.'; //to translate
$strPrivilegesReloaded = 'The privileges were reloaded successfully.'; //to translate
$strResourceLimits = 'Resource limits'; //to translate
$strUserOverview = 'User overview'; //to translate
$strZeroRemovesTheLimit = 'Note: Setting these options to 0 (zero) removes the limit.'; //to translate

$strPasswordChanged = 'The Password for %s was changed successfully.'; // to translate

$strDeleteAndFlush = 'Delete the users and reload the privileges afterwards.'; //to translate
$strDeleteAndFlushDescr = 'This is the cleanest way, but reloading the privileges may take a while.'; //to translate
$strDeleting = 'Deleting %s'; //to translate
$strJustDelete = 'Just delete the users from the privilege tables.'; //to translate
$strJustDeleteDescr = 'The &quot;deleted&quot; users will still be able to access the server as usual until the privileges are reloaded.'; //to translate
$strReloadingThePrivileges = 'Reloading the privileges'; //to translate
$strRemoveSelectedUsers = 'Remove selected users'; //to translate
$strRevokeAndDelete = 'Revoke all active privileges from the users and delete them afterwards.'; //to translate
$strRevokeAndDeleteDescr = 'The users will still have the USAGE privilege until the privileges are reloaded.'; //to translate
$strUsersDeleted = 'The selected users have been deleted successfully.'; //to translate

$strAddPrivilegesOnDb = 'Add privileges on the following database'; //to translate
$strAddPrivilegesOnTbl = 'Add privileges on the following table'; //to translate
$strColumnPrivileges = 'Column-specific privileges'; //to translate
$strDbPrivileges = 'Database-specific privileges'; //to translate
$strLocalhost = 'Local';
$strLoginInformation = 'Login Information'; //to translate
$strTblPrivileges = 'Table-specific privileges'; //to translate
$strThisHost = 'This Host'; //to translate
$strUserNotFound = 'The selected user was not found in the privilege table.'; //to translate
$strUserAlreadyExists = 'The user %s already exists!'; //to translate
$strUseTextField = 'Use text field'; //to translate

$strNoUsersSelected = 'No users selected.'; //to translate
$strDropUsersDb = 'Drop the databases that have the same names as the users.'; //to translate
$strAddedColumnComment = 'Added comment for column';  //to translate
$strWritingCommentNotPossible = 'Writing of comment not possible';  //to translate
$strAddedColumnRelation = 'Added relation for column';  //to translate
$strWritingRelationNotPossible = 'Writing of relation not possible';  //to translate
$strImportFinished = 'Import finished';  //to translate
$strFileCouldNotBeRead = 'File could not be read';  //to translate
$strIgnoringFile = 'Ignoring file %s';  //to translate
$strThisNotDirectory = 'This was not a directory';  //to translate
$strAbsolutePathToDocSqlDir = 'Please enter the absolute path on webserver to docSQL directory';  //to translate
$strImportFiles = 'Import files';  //to translate
$strDBGModule = 'Module';  //to translate
$strDBGLine = 'Line';  //to translate
$strDBGHits = 'Hits';  //to translate
$strDBGTimePerHitMs = 'Time/Hit, ms';  //to translate
$strDBGTotalTimeMs = 'Total time, ms';  //to translate
$strDBGMinTimeMs = 'Min time, ms';  //to translate
$strDBGMaxTimeMs = 'Max time, ms';  //to translate
$strDBGContextID = 'Context ID';  //to translate
$strDBGContext = 'Context';  //to translate
$strCantLoad = 'cannot load %s extension,<br />please check PHP Configuration';  //to translate
$strDefaultValueHelp = 'For default values, please enter just a single value, without backslash escaping or quotes, using this format: a';  //to translate
$strCheckPrivs = 'Check Privileges';  //to translate
$strCheckPrivsLong = 'Check privileges for database &quot;%s&quot;.';  //to translate
$strDatabasesStatsHeavyTraffic = 'Note: Enabling the Database statistics here might cause heavy traffic between the webserver and the MySQL one.';  //to translate
$strDatabasesStatsDisable = 'Disable Statistics';  //to translate
$strDatabasesStatsEnable = 'Enable Statistics';  //to translate
$strJumpToDB = 'Jump to database &quot;%s&quot;.';  //to translate
$strDropSelectedDatabases = 'Drop Selected Databases';  //to translate
$strNoDatabasesSelected = 'No databases selected.';  //to translate
$strDatabasesDropped = '%s databases have been dropped successfully.';  //to translate
$strGlobal = 'global';  //to translate
$strDbSpecific = 'database-specific';  //to translate
$strUsersHavingAccessToDb = 'Users having access to &quot;%s&quot;';  //to translate
$strChangeCopyUser = 'Change Login Information / Copy User';  //to translate
$strChangeCopyMode = 'Create a new user with the same privileges and ...';  //to translate
$strChangeCopyModeCopy = '... keep the old one.';  //to translate
$strChangeCopyModeJustDelete = ' ... delete the old one from the user tables.';  //to translate
$strChangeCopyModeRevoke = ' ... revoke all active privileges from the old one and delete it afterwards.';  //to translate
$strChangeCopyModeDeleteAndReload = ' ... delete the old one from the user tables and reload the privileges afterwards.';  //to translate
$strWildcard = 'wildcard';  //to translate
?>

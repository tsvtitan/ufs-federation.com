#!/usr/bin/perl -w
# ampbackup.pl Copyright (C) 2005 VerCom Systems, Inc. & Ron Hartmann (rhartmann@vercomsystems.com)
# Asterisk Management Portal Copyright (C) 2004 Coalescent Systems Inc. (info@coalescentsystems.ca)

# this program is in charge of looking into the database to pick up the backup sets name and options
# Then it creates the tar files and places them in the /var/lib/asterisk/backups folder
#
# The program if run from asterisk users crontab it is run as ampbackup.pl <Backup Job Record Number in Mysql> 
# OR
# The program is called from the backup.php script and implemented immediately as such:
# ampbackup.pl <Backup_Name> <Backup_Voicemail_(yes/no)> <Backup_Recordings_(yes/no)> <Backup_Configuration_files(yes/no)> 
# <Backup_CDR_(yes/no)> <Backup_FOP_(yes/no)
#
# example ampbackup.pl "My_Nightly_Backup" yes yes no no yes
#

# This program is free software; you can redistribute it and/or
# modify it under the terms of the GNU General Public License
# as published by the Free Software Foundation; either version 2
# of the License, or (at your option) any later version.

# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.


use DBI;

if (scalar @ARGV < 1)
{
         print "Usage: $0 Backup-set-ID \n";
         print "  This script Reads the backup options from the BackupTable.\n";
         print "    then runs the backup picking up the items that were turned\n";
         print "    OR\n";
         print "    \n";
         print "    The program is called from the backup.php script and implemented immediately as such:\n";
         print "    ampbackup.pl <Backup_Name> <Backup_Voicemail_(yes/no)> <Backup_Recordings_(yes/no)> <Backup_Configuration_files(yes/no)>\n";
         print "    <Backup_CDR_(yes/no)> <Backup_FOP_(yes/no)\n";
         print "    \n";
         print "    example ampbackup.pl \"My_Nightly_Backup\" yes yes no no yes\n";
         exit(1);
}

################### BEGIN OF CONFIGURATION ####################

# the name of the extensions table
$table_name = "Backup";
# WARNING: this file will be overwritten by the output of this program
# the name of the box the MySQL database is running on
$hostname = "localhost";
# the name of the database our tables are kept
# Now taken from $User_Preferences
# $database = "asterisk";
# scratch file to ftp results with
$ftpfile = "/tmp/freepbx-backup.ftp";

# Set Default if not defined below
$User_Preferences{"AMPDBUSER"}  = "asteriskuser";
$User_Preferences{"AMPDBPASS"}  = "amp109";
$User_Preferences{"AMPDBNAME"}  = "asterisk";
$User_Preferences{"AMPWEBROOT"} = "/var/www/html";
$User_Preferences{"ASTETCDIR"} = "/etc/asterisk";

$User_Preferences{"CDRDBNAME"} = "asteriskcdrdb";

$User_Preferences{"AMPPROVROOT"} = "";
$User_Preferences{"AMPPROVEXCLUDE"} = "";

$User_Preferences{"FTPBACKUP"} = "";
$User_Preferences{"FTPUSER"} = "";
$User_Preferences{"FTPPASSWORD"} = "";
$User_Preferences{"FTPSUBDIR"} = "";
$User_Preferences{"FTPSERVER"} = "";

$User_Preferences{"SSHBACKUP"} = "";
$User_Preferences{"SSHUSER"} = "";
$User_Preferences{"SSHRSAKEY"} = "";
$User_Preferences{"SSHSUBDIR"} = "";
$User_Preferences{"SSHSERVER"} = "";

open(FILE, "/etc/amportal.conf") || die "Failed to open amportal.conf\n";
while (<FILE>) {
    chomp;                  # no newline
    s/#.*//;                # no comments
    s/^\s+//;               # no leading white
    s/\s+$//;               # no trailing white
    next unless length;     # anything left?
    my ($var, $value) = split(/\s*=\s*/, $_, 2);
    $User_Preferences{$var} = $value;
}
close(FILE);

open(FILE, $User_Preferences{"ASTETCDIR"}."/asterisk.conf") || die "Failed to open asterisk.conf\n";
while (<FILE>) {
	chomp;
	s/\s//g;	# No spaces, anywhere.
	s/#.*//;	# no comments
	s/^\s+//;	# no leading white
	s/\s+$//;	# no trailing white
	if (/(.+)=>(.+)/) {
		$ast{$1} = $2;
	}
}

$backupdir = $ast{'astvarlibdir'}."/backups";

# username to connect to the database
$username = $User_Preferences{"AMPDBUSER"} ;
# password to connect to the database
$password = $User_Preferences{"AMPDBPASS"};
# Database name
$database = $User_Preferences{"AMPDBNAME"};
# Database host
$hostname = $User_Preferences{"AMPDBHOST"};
# the WEB ROOT directory 
$webroot = $User_Preferences{"AMPWEBROOT"};
# CDR database 
$cdrdatabase = $User_Preferences{"CDRDBNAME"};

# Provisioning root(s) and exclude list, if phone configurations should be backed up
#
$provroot = $User_Preferences{"AMPPROVROOT"};
$excludefile = $User_Preferences{"AMPPROVEXCLUDE"};

# If and where to send the backup file once created (still left on local machine as well)
#
$ftpbackup = uc $User_Preferences{"FTPBACKUP"};
$ftpuser = $User_Preferences{"FTPUSER"};
$ftppassword = $User_Preferences{"FTPPASSWORD"};
$ftpsubdir = $User_Preferences{"FTPSUBDIR"};
$ftpserver = $User_Preferences{"FTPSERVER"};

$sshbackup = uc $User_Preferences{"SSHBACKUP"};
$sshuser = $User_Preferences{"SSHUSER"};
$sshrsakey = $User_Preferences{"SSHRSAKEY"};
$sshsubdir = $User_Preferences{"SSHSUBDIR"};
$sshserver = $User_Preferences{"SSHSERVER"};

################### END OF CONFIGURATION #######################
my $now = localtime time;
my ($sec,$min,$hour,$mday,$mon,$year, $wday,$yday,$isdst) = localtime time;
$year += 1900;
$mon +=1;
#my $Stamp="$year$mon$mday.$hour.$min.$sec";
my $Stamp=sprintf "%04d%02d%02d.%02d.%02d.%02d",$year,$mon,$mday,$hour,$min,$sec;

if (scalar @ARGV > 1)
{
	$Backup_Name = $ARGV[0];
	$Backup_Voicemail = $ARGV[1];
	$Backup_Recordings = $ARGV[2];
	$Backup_Configurations = $ARGV[3];
	$Backup_CDR = $ARGV[4];
	$Backup_FOP = $ARGV[5];
}
else
{
	$dbh = DBI->connect("dbi:mysql:dbname=$database;host=$hostname", "$username", "$password");

	$statement = "SELECT Name, Voicemail, Recordings, Configurations, CDR, FOP from $table_name where ID= $ARGV[0]";

	$result = $dbh->selectall_arrayref($statement);
	unless ($result) {
	  # check for errors after every single database call
	  print "dbh->selectall_arrayref($statement) failed!\n";
	  print "DBI::err=[$DBI::err]\n";
	  print "DBI::errstr=[$DBI::errstr]\n";
	}	

	@resultSet = @{$result};
	if ( $#resultSet == -1 ) {
	  print "No Backup Schedules defined in $table_name\n";
	  exit;
	}

	foreach my $row ( @{ $result } ) {
		$Backup_Name = @{ $row }[0];
		$Backup_Voicemail = @{ $row }[1];
		$Backup_Recordings = @{ $row }[2];
		$Backup_Configurations = @{ $row }[3];
		$Backup_CDR = @{ $row }[4];
		$Backup_FOP = @{ $row }[5];
		#print "$Backup_Name $Backup_Voicemail $Backup_Recordings $Backup_Configurations $Backup_CDR $Backup_FOP\n";
	}
}

	system ("/bin/rm -rf /tmp/ampbackups.$Stamp > /dev/null  2>&1");
	system ("/bin/mkdir /tmp/ampbackups.$Stamp > /dev/null  2>&1");
	if ( $Backup_Voicemail eq "yes" ){
		system ("/bin/tar -Pcz -f /tmp/ampbackups.$Stamp/voicemail.tar.gz ".$ast{'astspooldir'}."/voicemail");
	}
	if ( $Backup_Recordings eq "yes" ){
		system ("/bin/tar -Pcz -f /tmp/ampbackups.$Stamp/recordings.tar.gz ".$ast{'astvarlibdir'}."/sounds/custom");
	}
	if ( $Backup_Configurations eq "yes" ){
		system ($ast{'astvarlibdir'}."/bin/dumpastdb.php $Stamp > /dev/null");
		$config_files = "";
		$config_files .= "/etc/zaptel.conf" if -r "/etc/zaptel.conf";
		$config_files .= "/etc/dahdi" if -r "/etc/dahdi";
		system ("/bin/tar -Pcz -f /tmp/ampbackups.$Stamp/configurations.tar.gz ".$ast{'astvarlibdir'}."/agi-bin/ ".$ast{'astvarlibdir'}."/bin/ ".
				$User_Preferences{"ASTETCDIR"}." $webroot/admin /etc/amportal.conf $config_files /tmp/ampbackups.$Stamp/astdb.dump ");

		if ($provroot ne "") {
			$excludearg = "";
			if (-r $excludefile) {
				$excludearg = "--exclude-from $excludefile ";
			}
			system ("/bin/tar -Pcz $excludearg -f /tmp/ampbackups.$Stamp/phoneconfig.tar.gz $provroot ");
		}

		system ("mysqldump --add-drop-table -h $hostname -u $username -p$password --database $database > /tmp/ampbackups.$Stamp/asterisk.sql");
	}
	if ( $Backup_CDR eq "yes" ){
		system ("/bin/tar -Pcz -f /tmp/ampbackups.$Stamp/cdr.tar.gz $webroot/admin/cdr");
		system ("mysqldump --add-drop-table -h $hostname -u $username -p$password --database $cdrdatabase > /tmp/ampbackups.$Stamp/asteriskcdr.sql");
	}
	if ( $Backup_FOP eq "yes" ){
		system ("/bin/tar -Pcz -f /tmp/ampbackups.$Stamp/fop.tar.gz $webroot/panel");
	}
	system ("/bin/mkdir -p '$backupdir/$Backup_Name' > /dev/null  2>&1");
	system ("/bin/tar -Pcz -f '$backupdir/$Backup_Name/$Stamp.tar.gz' /tmp/ampbackups.$Stamp");
	system ("/bin/rm -rf /tmp/ampbackups.$Stamp > /dev/null  2>&1");
#
#
# FTP Sucessfull Backup's to FTPSERVER
#
# leave $ftpbackup which gets overwritten next time but can be checked to see if there were errors.
# IMPORTANT - if testing as root, delete files since backup runs as asterisk and will fail here since
#             root leave the file around and asterisk can't overwrite it.
#	      Note - the hardcoded full backup that cron does will overwrite each day at destination.
#
if ( $ftpbackup eq "YES" ) {
	open(FILE, ">$ftpfile") || die "Failed to open $ftpfile\n";
 
		printf FILE "user $ftpuser $ftppassword \n";
		printf FILE "binary\n";
			if ( $ftpsubdir ne "" ) {
				printf FILE "cd $ftpsubdir \n";
			}
			printf FILE "lcd $backupdir/$Backup_Name/\n";
			printf FILE "put $Stamp.tar.gz\n";
			printf FILE "bye\n";
			close(FILE);
 
			system ("ftp -n $ftpserver < $ftpfile > /dev/null  2>&1");
 
			#system ("/bin/rm -rf /tmp/ftp2cabana > /dev/null  2>&1");
}

if ( ($sshbackup eq "YES") && ($sshrsakey ne "") && ($sshserver ne "") ) {

	if ($sshuser eq "") {
		$sshuser = system("whoami");
	}
	if ($sshsubdir ne "") {
		system("/usr/bin/ssh -o StrictHostKeyChecking=no -i $sshrsakey $sshuser\@$sshserver mkdir -p $sshsubdir");
	}
	system("/usr/bin/scp -o StrictHostKeyChecking=no -i $sshrsakey $backupdir/$Backup_Name/$Stamp.tar.gz $sshuser\@$sshserver:$sshsubdir");
}

exit 0;

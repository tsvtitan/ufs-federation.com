��    l      |  �   �      0	  !   1	     S	     k	  	   t	     ~	     �	     �	  �   �	  �  �
  �   n  �   #  �  �    ~  \   �     �  Q    �  W  �     5     P  E  �   �  �  y  C    �   c          !     0  .   =  	   l  >   v  t   �  /   *     Z  g   a  n   �     8   2   H      {      �      �      �   
   �      �      �      �   `   �      ?!  }   G!     �!     �!  V   �!  ,   E"     r"  M   �"     �"     �"  H   �"     B#     S#     b#     �#     �#     $     $     0$     9$  �   H$  E   :%  &   �%     �%     �%     �%  '   �%     &     &     &     &     (&     ;&     G&     V&  	   i&  
   s&     ~&     �&     �&     �&     �&  
   �&  D   �&  	   $'     .'     ='     N'     ['     i'  v  m'     �(      �(  6   )     K)     P)     S)     h)  -   �)     �)     �)  n  �)  "   ;+  '   ^+     �+     �+     �+  #   �+     �+  �   �+  �  �,  �   �.  �   �/  �  �0  %  '2  ^   M3    �3  �  �4  �  |7  �   [9  8   M:  _  �:  �   �;  �  �<  M  �>  �   �@  
   �A     �A  
   �A  /   �A     �A  E   B  �   HB  8   �B     C  }   C  ~   �C     D  0   #D  
   TD     _D     kD      yD     �D     �D     �D     �D  �   �D  	   ZE  z   dE  	   �E  #   �E  Z   F  D   hF  '   �F  V   �F     ,G     1G  Q   FG     �G     �G  �   �G     JH     WH      jH     �H     �H     �H    �H  V   �I  !   J     <J     \J     vJ  ,   �J     �J     �J     �J     �J     �J     �J     �J     K     'K     8K     DK     YK     uK     �K     �K     �K  X   �K     L     %L     6L     GL     SL     `L  �  cL     �M  #   
N  J   .N     yN     ~N     �N     �N  0   �N     �N     O     S   )   5         @              ^   D   #       1   ]   3       M             L   X   ;       W          i      Y      :   0   ,   c   *                	       F   _   k                    V       ?   O         Q   /   4       \   (                               [       <   l   7   b          '       9   K           6                       G      .      f   B   P   
   j       A   E      -          >   g       =   h       H       J       R   I       Z   N   a   %      !   C   8   `   &       2       U              d       $                  +   "   T   e    %s must be a non-negative integer %s must be alphanumeric Adaptive Add Field Add Local Network Field Advanced General Settings Allow SIP Guests Asterisk NAT setting:<br /> yes = Always ignore info and assume NAT<br /> no = Use NAT mode only according to RFC3581 <br /> never = Never attempt NAT mode or RFC3581 <br /> route = Assume NAT, don't send rport Asterisk: allowguest. When set Asterisk will allow Guest SIP calls and send them to the Default SIP context. Turning this off will keep anonymous SIP calls from entering the system. However, the Allow Anonymous SIP calls from the General Settings section will not function. Allowing guest calls but rejecting the Anonymous SIP calls in the General Section will enable you to see the call attempts and debug incoming calls that may be mis-configured and appearing as guests. Asterisk: bindaddr. The IP address to bind to and listen for calls on the Bind Port. If set to 0.0.0.0 Asterisk will listen on all addresses. It is recommended to leave this blank. Asterisk: bindport. Local incoming UDP Port that Asterisk will bind to and listen for SIP messages. The SIP standard is 5060 and in most cases this is what you want. It is recommended to leave this blank. Asterisk: canreinvite. yes: standard reinvites; no: never; nonat: An additional option is to allow media path redirection (reinvite) but only when the peer where the media is being sent is known to not be behind a NAT (as the RTP core can determine it based on the apparent IP address the media arrives from; update: use UPDATE for media path redirection, instead of INVITE. (yes = update + nonat) Asterisk: context. Default context for incoming calls if not specified. FreePBX sets this to from-sip-external which is used in conjunction with the Allow Anonymous SIP calls. If you change this you will effect that behavior. It is recommended to leave this blank. Asterisk: externrefresh. How often to lookup and refresh the External Host FQDN, in seconds. Asterisk: g726nonstandard. If the peer negotiates G726-32 audio, use AAL2 packing order instead of RFC3551 packing order (this is required for Sipura and Grandstream ATAs, among others). This is contrary to the RFC3551 specification, the peer _should_ be negotiating AAL2-G726-32 instead. Asterisk: jbenable. Enables the use of a jitterbuffer on the receiving side of a SIP channel. An enabled jitterbuffer will be used only if the sending side can create and the receiving side can not accept jitter. The SIP channel can accept jitter, thus a jitterbuffer on the receive SIP side will be used only if it is forced and enabled. An example is if receiving from a jittery channel to voicemail, the jitter buffer will be used if enabled. However, it will not be used when sending to a SIP endpoint since they usually have their own jitter buffers. See jbforce to force it's use always. Asterisk: jbforce. Forces the use of a jitterbuffer on the receive side of a SIP channel. Normally the jitter buffer will not be used if receiving a jittery channel but sending it off to another channel such as another SIP channel to an endpoint, since there is typically a jitter buffer at the far end. This will force the use of the jitter buffer before sending the stream on. This is not typically desired as it adds additional latency into the stream. Asterisk: jbimpl. Jitterbuffer implementation, used on the receiving side of a SIP channel. Two implementations are currently available:<br /> fixed: size always equals to jbmaxsize;<br /> adaptive: with variable size (the new jb of IAX2). Asterisk: jblog. Enables jitter buffer frame logging. Asterisk: jbmaxsize. Max length of the jitterbuffer in milliseconds.<br /> Asterisk: jbresyncthreshold. Jump in the frame timestamps over which the jitterbuffer is resynchronized. Useful to improve the quality of the voice, with big jumps in/broken timestamps, usually sent from exotic devices and programs. Can be set to -1 to disable. Asterisk: minexpiry. Minimum length of registrations/subscriptions.<br /> Asterisk: maxepiry. Maximum allowed time of incoming registrations<br /> Asterisk: defaultexpiry. Default length of incoming and outgoing registrations. Asterisk: registertimeout. Retry registration attempts every registertimeout seconds until successful or until registrationattempts tries have been made.<br /> Asterisk: registrationattempts. Number of times to try and register before giving up. A value of 0 means keep trying forever. Normally this should be set to 0 so that Asterisk will continue to register until successful in the case of network or gateway outages. Asterisk: rtptimeout. Terminate call if rtptimeout seconds of no RTP or RTCP activity on the audio channel when we're not on hold. This is to be able to hangup a call in the case of a phone disappearing from the net, like a powerloss or someone tripping over a cable.<br /> Asterisk: rtpholdtimeout. Terminate call if rtpholdtimeout seconds of no RTP or RTCP activity on the audio channel when we're on hold (must be > rtptimeout). <br /> Asterisk: rtpkeepalive. Send keepalives in the RTP stream to keep NAT open during periods where no RTP stream may be flowing (like on hold). Asterisk: t38pt_udptl. Enables T38 passthrough if enabled. This SIP channels that support sending/receiving T38 Fax codecs to pass the call. Asterisk can not process the media. Audio Codecs Auto Configure Bind Address Bind Address (bindaddr) must be an IP address. Bind Port Bind Port (bindport) must be between 1024..65535, default 5060 Check the desired codecs, all others will be disabled unless explicitly enabled in a device or trunks configuration. Check to enable and then choose allowed codecs. Codecs Control whether subscriptions INUSE get sent ONHOLD when call is placed on hold. Useful when using BLF. Control whether subscriptions already INUSE get sent RINGING when another call is sent. Useful when using BLF. Default Context Default Language for a channel, Asterisk: language Disable Disabled Dynamic Host Dynamic Host can not be blank Dynamic IP ERRORS Edit Settings Enable Enable Asterisk srvlookup. See current version of Asterisk for limitations on SRV functionality. Enabled External FQDN as seen on the WAN side of the router and updated dynamically, e.g. mydomain.dyndns.com. (asterisk: externhost) External IP External IP can not be blank External Static IP or FQDN as seen on the WAN side of the router. (asterisk: externip) Failed to auto-detect local network settings Failed to auto-detect settings File %s should not have any settings in it. Those settings should be removed. Fixed Force Jitter Buffer Frequency in seconds to check if MWI state has changed and inform peers. IP Configuration Implementation Indicate whether the box has a public IP or requires NAT settings. Automatic configuration of what is often put in sip_nat.conf Jitter Buffer Jitter Buffer Logging Jitter Buffer Settings Jitter Buffer Size Language Local Networks Local network settings (Asterisk: localnet) in the form of ip/mask such as 192.168.1.0/255.255.255.0. For networks with more 1 lan subnets, use the Add Local Network Field button for more fields. Blank fields will be removed upon submitting. Localnet netmask must be formated properly (e.g. 255.255.255.0 or 24) Localnet setting must be an IP address MEDIA & RTP Settings MWI Polling Freq Max Bit Rate Maximum bitrate for video calls in kb/s NAT NAT Settings No Non-Standard g726 Notification & MWI Notify Hold Notify Ringing Other SIP Settings Public IP RTP Timers Refresh Rate Registration Settings Registration Times Registrations Reinvite Behavior SRV Lookup Settings in %s may override these. Those settings should be removed. Static IP Submit Changes T38 Pass-Through Video Codecs Video Support Yes You may set any other SIP settings not present here that are allowed to be configured in the General section of sip.conf. There will be no error checking against these settings so check them carefully. They should be entered as:<br /> [setting] = [value]<br /> in the boxes below. Click the Add Field box to add additional fields. Blank boxes will be deleted when submitted. already exists checking for sipsettings table.. fatal error occurred populating defaults, check module kb/s no none, creating table populating default codecs.. rtpholdtimeout must be higher than rtptimeout ulaw, alaw, gsm added yes Project-Id-Version: FreePBX sipsettings
Report-Msgid-Bugs-To: 
POT-Creation-Date: 2009-08-29 22:01+0200
PO-Revision-Date: 2009-08-30 10:42+0100
Last-Translator: Mikael Carlsson <mickecamino@gmail.com>
Language-Team: Swedish
MIME-Version: 1.0
Content-Type: text/plain; charset=utf-8
Content-Transfer-Encoding: 8bit
X-Poedit-Language: Swedish
X-Poedit-Country: SWEDEN
 %s måste vara ett positivt heltal %s måste vara ett alfanumeriskt värde Adaptiv Lägg till fält Lägg till lokalt nätverk Avancerade generella inställningar Tillåt SIP-gäster Asterisk NAT-inställningar:<br /> yes = Ignorera alltid info och förutsätt NAT<br /> no = Använd NAT-läge enligt RFC3581 <br /> never = Använd aldrig NAT-läge eller RFC3581 <br /> route = Förutsätt NAT, sänd inte rport  Asterisk: allowguest. När denna inställning är satt till Ja kommer Asterisk att tillåta gästsamtal och skicka dom till standard SIP-sammanhanget. Om detta sätts till Nej kommer inga gästsamtal att tillåtas. Emellertid kommer då inställningen i Generella inställningar, Tillåt anonyma samtal, att sluta fungera. Att tillåta gäster men inte anonyma samtal ger dig möjlighet att se alla samtalsförsök och avlusa inkommande samtal som kan vara felaktigt konfigurerade och uppträda som gäst. Asterisk: bindaddr. IP-adressen att binda till och lyssna efter samtal på Bindporten. Om detta sätts till 0.0.0.0 kommer Asterisk att lyssna på alla adresser. Det är rekommenderat att lämna detta fält tomt. Asterisk: bindport. Lokal inkommande UDP-port som Asterisk binder sig till och lyssnar efter SIP-meddelanden. Standardporten för SIP är 5060 och i de flesta fall är vad du vill ha. Det är rekommenderat att lämna detta fält tomt. Asterisk: canreinvite. ja: standard reinvites; nej: aldrig; nonat: Ett extra val för att tillåta omstyrning av mediaströmmen (reinvite) men endast när peer där strömmen skickas till är känd att inte vara bakom NAT (eftersom RTP kan bestämma det baserat på den synbara IP-adressen strömmen kommer från; update: använd UPDATE för mediaomstyrning i stället för INVITE. (yes = update + nonat) Asterisk: context. Standard sammanhang för inkommande samtal om det inte sätts här. FreePBX sätter detta till from-sip-external som används i samband med Tillåt SIP-gäster. Om du ändrar detta kommer du att påverka den inställningen. Det är rekommenderat att lämna detta fält tomt. Asterisk: externrefresh. Hur ofta uppslag och uppdatering ska ske för extern FQDN i sekunder. Asterisk: g726nonstandard. Om peer förhandlar G726-32 ljud, använd AAL2 packningsföljd i stället för RFC3551 (detta krävs bland annat för Sipura och Grandstream ATAs). Detta är i motsats till RFC3551 specifikationen där peer _borde_ förhandla AAL2-G726-32 i stället. Asterisk: jbenable. Aktiverar användandet av jitterbuffer på den mottagande sidan av en SIP-kanal. En aktiverad jitterbuffer används bara om den sändande sidan kan skapa och den mottagande sidan inte kan acceptera jitter. SIP-kanalen kan acceptera jitter, en jitterbuffer på den mottagande SIP-sidan kommer bara att användas om den är forcerad och aktiverad. Ett exempel är om mottagning sker från en jitterkanal till röstbrevlådan kommer jitterbuffern att användas om den är aktiverad. Emellertid kommer detta inte användas när det skickas till en SIP-enhet eftersom dom vanligtvis har deras egna jitterbuffrar. Se även jbforce för att forcera jitterbuffrar att alltid användas. Asterisk: jbforce. Tvingar användandet av en jitterbuffer på mottagande sidan av en SIP-kanal. Normalt kommer inte jitterbuffern att användas vid mottagande av en jittery-kanal men skickar den till en annan kanal såsom en SIP-kanal till en ändutrusting eftersom det typiskt finns en jitterbuffer i slutet av kedjan. Detta tvingar användandet av en jitterbuffer innan mediaströmmen skickas vidare. Detta används inte normal då det ökar fördröjningen i mediaströmmen. Asterisk: jbimpl. Jitterbuffer implementation, används på den mottagande SIP-kanalen. Två implementationer finns för närvarande:<br /> fast: storleken alltid lika med jbmaxsize;<br /> adaptiv: med variabel storlek (den nya jb på IAX2). Asterisk: jblog. Aktiverar loggning av jitterbufferramar Asterisk: jbmaxsize. Max längd på jitterbuffern i storlek.<br /> Asterisk: jbresyncthreshold. Hopp i tiden inom ramen där jitterbuffern återsynkroniseras. Användbart för att öka kvaliteten på rösten med stora hopp i / eller brutna tidsstämplar, vanligtvis skickat från exotiska enheter och program. Kan sättas till -1 för att avaktiveras. Asterisk: minexpiry. Minsta längd på registrering/prenumeration.<br /> Asterisk: maxepiry. Maximal tillåten tid för inkommande registreringar.<br /> Asterisk: defaultexpiry. Standardlängd för inkommande och utgående registreringar. Asterisk: registertimeout. Försök registrera igen efter det antal sekunder i värdet registertimeout till en lyckad registrering sker eller till det antal specificerat i registrationattempts har skett.<br /> Asterisk: registrationattempts. Antal gånger registrering ska försökas. Ett värde på 0 är oändligt antal försök. Normalt ska detta stå på 0 för att Asterisk kan fortsätta registrering efter ström- eller nätavbrott. Asterisk: rtptimeout. Avsluta samtalet om aktiviteten på RTP eller RTCP har upphört i mer än rtptimeout i sekunder. Detta för att kunna lägga på samtalet ifall telefonen försvinner på nätverket i händelse av strömavbrott eller om någon kopplar loss kabeln till telefonen.<br /> Asterisk: rtpholdtimeout. Avsluta samtalet om på RTP eller RTCP har upphört när samtalet ligger på vänt (värdet måste vara > rtptimeout). <br /> Asterisk: rtpkeepalive. Skicka keepalives i RTP-strömmen för att hålla NAT öppet under perioder när ingen RTP-ström finns (samtal på vänt). Asterisk: t38pt_udptl. Tillåter T38 passthrough om detta är aktiverat. This SIP channels that support sending/receiving T38 Fax codecs to pass the call. Asterisk can not process the media. Ljud-codec Automatisk konfiguration Bindadress Bindadress (bindaddr) måste vara en IP-adress. Bindport Bindport (bindport) måste vara mellan 1024..65535, standard är 5060 Markera dom önskade codecarna, alla andra kommer att avaktiveras om dom inte är explicit definierade i enheter eller trunkkonfigurering. Markera för att aktivera, välj sedan tillåtna codecs. Codec Används för prenumerationer som är INUSE får skickat till sig ONHOLD när ett samtal är på vänt. Användbart för BLF. Används för prenumerationer som är INUSE får skickat till sig RINGING när ett annat samtal skickas. Användbart för BLF. Standard sammanhang Standardspråk för en kanal. Asterisk: language Avaktivera Avaktiverad Dynamisk host Dynamisk host kan inte vara tomt Dynamisk IP-adress FEL Redigera inställningar Aktivera Aktivera Asterisk srvlookup. Läs i dokumentationen för din version av Asterisk för att de begränsningar som finns i funktionen SRV. Aktiverad Extern FQDN som är på WAN-sidan av routern och som uppdateras dynamiskt, eg. mydomain.dyndns.com. (asterisk: externhost) Extern IP Extern IP-adress kan inte vara tomt Extern statisk IP-adress eller FQDN som är på WAN-sidan av routern. (asterisk: externip) Det gick inte att auto-detektera de lokala nätverksinställningarna Kunde inte autodetektera inställningar Filen %s ska inte ha några inställningar alls. Dessa inställningar måste tas bort. Fast Forcera jitterbuffer Antal sekunder mellan kontrollerna om MWI har ändrat läge, meddela sedan peers. IP-konfiguration Implementation Indikerar om datorn har en publik IP-adress eller kräver NAT-inställningar. Automatisk konfiguration av vad som oftast skrivs in i sip_nat.conf Jitterbuffer Logga jitterbuffer Inställningar för Jitterbuffer Storlek på jitterbuffer Språk Lokalt nätverk Lokal nätverksinställning (Asterisk: localnet) i formen av ip/mask såsom 192.168.1.0/255.255.255.0. För nätverk med mer än ett subnät klickar du på knappen Lägg till lokalt nätverk för att lägga till fler fält. Tomma fält tas bort när sidan sparas. Nätmasken för localnet måste vara korrekt formaterat (t.ex. 255.255.255.0 eller 24) Localnet måste vara en IP-adress Inställningar för MEDIA & RTP Kontrollfrekvens för MWI Max bithastighet Maximal bithastighet i kb/s för videosamtal NAT NAT-inställningar Nej Icke-standard g726 Meddelande & MWI Notify hold Notify ringing Andra SIP-inställningar Publik IP-adress RTP-klockor Uppdateringsfrekvens Registreringsinställningar Registreringstider Registreringar Beteende för reinvite SRV-uppslag Inställningar i %s kan åsidosätta inställningarna du gör här. Du bör ta bort dom. Statisk IP-adress Spara ändringar T38 Pass-Through Videocodecs Videosupport Ja Du kan göra fler SIP-inställningar som inte visas här men som är tillåtna att konfigurera i den generella sektionen av sip.conf. Ingen felkontroll kommer att göras mot dessa inställningar så kontrollera dom noga. Syntaxen för värdena är <br /> [inställning] = [värde]<br />  i fälten nedan. Klicka på Lägg till fält för att lägga till fler. Tomma fält kommer att tas bort när sidan sparas. finns redan kontrollerar tabellen sipsettings.. allvarligt fel inträffade när standardvärde skrevs, kontrollera modulen kb/s nej ingen hittad, skapar tabell skriver standard codecs.. rtpholdtimeout måste vara högre än rtptimeout la till ulaw, alaw, gsm ja 
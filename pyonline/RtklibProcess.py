#!/usr/bin/python3
"""
@author: Farah BATTIKH
"""


import re
import os
import numpy as np
import gpstime as gps
import gnsstoolbox.rinex_o as rx
import xml.etree.ElementTree as ET
import RtklibUtils as utils
from Station import Station

class rtklib_process():
    def __init__(self):
        self.strategy = 'static'
        self.station_number = 10
        self.max_distance = 100
        self.mail = ""
        self.directory = "" 
        self.RnxFileList = []
        self.all_stations =[]
        self.proche_stations_names =[]
        self.proche_stations_list = []
        self.tStart = gps.gpstime()
        self.tEnd = gps.gpstime()
        self.projectPath =""
        self.observationPath = ""
        self.exeConfPath =  ""

        
    def read(self,filename):
            tree = ET.parse(filename)
            root = tree.getroot()
            for child in root:
                if child.tag=='options':
                    for child2 in child:
                        if re.search(child2.tag,'strategy'):
                            self.strategy = child2.text
                        if re.search(child2.tag,'station_number'):
                            self.station_number = int(child2.text)
                        if re.search(child2.tag,'max_distance'):
                            self.max_distance = int(child2.text)
                        if re.search(child2.tag,'user_mail'):
                            self.mail = child2.text

                if child.tag=='files':
                    for child2 in child:
                        self.RnxFileList.append(child2[1].text)

    def __str__(self):
        s = "%-20s: %s\n" % ("Strategy",self.strategy)
        s+= "%-20s: %s\n" % ("Station number",self.station_number)
        s+= "%-20s: %s\n" % ("Max distance",self.max_distance)
        s+= "%-20s: %s\n" % ("Mail",self.mail)

        s+= "Files :\n"
        for f in self.RnxFileList:
             s+= "- %s\n" % (f)

        return s
    def rinex_info(self, filename):
        """
        fonction qui s'en sert de fichier rinex pour déduire les stations plus proches et les coord approx du 
        récepteur
        """
        myrinex = rx.rinex_o()
        ret = myrinex.load_rinex_o(filename)  # à remplacer par LoadRinexO(filename)
        print("return",ret)
        head= myrinex.headers[0]
        if hasattr (head,'X'):
            Xrec = head.X
        if hasattr (head,'Y'):
            Yrec = head.Y   
        if hasattr (head,'Z'):
            Zrec = head.Z
        
        if hasattr (head,'TIME_OF_FIRST_OBS'):
            self.tStart = head.TIME_OF_FIRST_OBS
        if hasattr (head,'TIME_OF_LAST_OBS'):
            self.tEnd = head.TIME_OF_LAST_OBS

        #lecture stations
        stations_fichier = open(os.path.join(self.exeConfPath,"stations.txt"), "r")
        stations_texte = stations_fichier.readlines() #liste lignes du fichier
        stations_fichier.close()
        # on va créer attribut all_stations qui contient les ligne du fichier splité selon le format
        # on appel au constructeur pour créer les objet station et on ajoute a chaque fois l'objet 
        # à la la liste
        self.all_stations=[]
        for line in stations_texte:
            data_station=line.split()
            self.all_stations.append(Station(data_station[0],float(data_station[1]),float(data_station[2]),float(data_station[3])) )
        
        for station in self.all_stations:
            station.calc_dist(Xrec,Yrec,Zrec)
        # all_stations_sorted est une liste de toutes les objets Stations trié par leur distance % au rec
        all_stations_sorted = sorted(self.all_stations,key=lambda station : station.last_dist )# objet entré Station (nommé station)critére detrie station.last_dist
       
        all_stations_sorted_filtred =list( filter(lambda station : station.last_dist < self.max_distance*1000 , all_stations_sorted))

        proche_stations =self.prepare_proche_station(all_stations_sorted_filtred,self.station_number)

        self.proche_stations_list = proche_stations
        self.proche_stations_names = [station.nom for station in proche_stations]
        
        print("approximated coordinate",Xrec,Yrec,Zrec)
        return (Xrec,Yrec,Zrec, head)

    def prepare_proche_station(self,all_sorted_list,station_number):
        """ this function would find the n nearest stations and that have dataon the ftp server
            at the date of observation 
            output : the list of name of the n nearest stations     
        """        
        
        ftp = utils.connexionftp(self.exeConfPath) 
        proche_stations_list =[]
        ficftp_dir = "pub/data/"+str(self.tStart.yyyy)+"/"+str(self.tStart.doy)+"/data_30"
        ftp.cwd(ficftp_dir)
        curennt_ftp_dir = ftp.pwd()
        ftp.cwd("/")
        ftp.cwd(ficftp_dir)
        ftp_list_file = ftp.nlst() # la liste des fichiers dans la réportoire ftp
        for stat in   all_sorted_list :
            ficftp_name = stat.nom.lower()+str(self.tStart.doy)+str("0.")+str(self.tStart.yy)+"d.Z"
            
            if ficftp_name in ftp_list_file:
                print(ficftp_name,  " is in this directory")
                proche_stations_list.append(stat)
        
            if (len(proche_stations_list) == station_number ):
                break
        ftp.cwd("/")
        ftp.cwd(curennt_ftp_dir) 
        ftp.quit()
        return proche_stations_list

           
    def downloadftp(self, ftp,ficftp, repdsk='.', ficdsk=None):
        """télécharge le fichier ficftp du serv. ftp dans le rép. repdsk du disque
           - ftp: variable 'ftplib.FTP' sur une session ouverte
           - ficftp: nom du fichier ftp dans le répertoire courant
           - repdsk: répertoire du disque dans lequel il faut placer le fichier
           - ficdsk: si mentionné => c'est le nom qui sera utilisé sur disque
        """
        
        #ficftp_name est le nom de fichier à télécharger à partir de la station et fictftp_dir son current directory
        ficftp_dir, ficftp_name = os.path.split(ficftp)        
        curennt_ftp_dir = ftp.pwd()
        ftp.cwd("/")
        ftp.cwd(ficftp_dir)
        ftp_list_file = ftp.nlst() # la liste des fichiers dans la réportoire ftp
        if not ficftp_name in ftp_list_file:
            print(ficftp_name,  " is not in this directory")
        else:
            
            if ficdsk==None:
                ficdsk=ficftp_name
                
            with open(os.path.join(repdsk, ficdsk), 'wb') as f:
                ftp.retrbinary('RETR ' + ficftp_name, f.write)
                downloadedFilePath = os.path.join(repdsk,ficftp_name)
                print("file :",downloadedFilePath," is successfully downloaded")
        ftp.cwd("/")
        ftp.cwd(curennt_ftp_dir) 

        
        
    def download_radio(self, ftp,ficftp, repdsk='.', ficdsk=None):
        """télécharge le fichier ficftp du serv. ftp dans le rép. repdsk du disque
           - ftp: variable 'ftplib.FTP' sur une session ouverte
           - ficftp: nom du fichier ftp dans le répertoire courant
           - repdsk: répertoire du disque dans lequel il faut placer le fichier
           - ficdsk: si mentionné => c'est le nom qui sera utilisé sur disque
        """
        
        #ficftp_name est le nom de fichier à télécharger à partir de la station et fictftp_dir son current directory
        ficftp_dir, ficftp_name1 = os.path.split(ficftp)        
        ficftp_name2 ="brdm"+ficftp_name1[4:-1]
        ficftp_names = [ficftp_name1, ficftp_name2]
        #ficftp_names contient la liste de possibilité des nom des fichiers des orbites radiodiffusé
        curennt_ftp_dir = ftp.pwd()
        ftp.cwd("/")
        ftp.cwd(ficftp_dir)
        ftp_list_file = ftp.nlst()
        
        ficftp_name = None
        for name in ficftp_names:
            if not name in ftp_list_file:
                print(name,  " is not in this directory")
            else :
                ficftp_name = name
                break
            
        if ficftp_name:            
            if ficdsk==None:
                ficdsk=ficftp_name
                
            with open(os.path.join(repdsk, ficdsk), 'wb') as f:
                ftp.retrbinary('RETR ' + ficftp_name, f.write)
                downloadedFilePath = os.path.join(repdsk,ficftp_name)
        ftp.cwd("/")
        ftp.cwd(curennt_ftp_dir)  
        
    def download_or_precise(self, ftp,ficftp, repdsk='.', ficdsk=None):
        """download precise orbite 
        """
        ficftp_dir, ficftp_name1 = os.path.split(ficftp) 
        ficftp_name2 = "igr"+ficftp_name1[3:]
        ficftp_name3 = "igu"+ficftp_name1[3:]
        ficftp_names = [ficftp_name1, ficftp_name2,ficftp_name3]
        curennt_ftp_dir = ftp.pwd()
        ftp.cwd("/")
        ftp.cwd(ficftp_dir)
        ftp_list_file = ftp.nlst()
        # boucle igs en premier lieu s'il le trouve pas igr sinon igu
        ficftp_name = None
        for name in ficftp_names:
            if not name in ftp_list_file:
                print(name,  " is not in this directory")
            else :
                ficftp_name = name
                break
            
        if ficftp_name:            
            if ficdsk==None:
                ficdsk=ficftp_name
                
            with open(os.path.join(repdsk, ficdsk), 'wb') as f:
                ftp.retrbinary('RETR ' + ficftp_name, f.write)
        
        ftp.cwd("/")
        ftp.cwd(curennt_ftp_dir) 
        print("precise succées,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,")
      
    def unzip(self, obs_dir):
        for file in os.listdir(obs_dir):
            if (os.path.isfile(os.path.join(obs_dir,file)) and file.endswith("Z")):
                os.system("gzip -d "+os.path.join(obs_dir,file))
                
                
    def gzip_crx(self, obs_dir):
        for file in os.listdir(obs_dir):
            if file.endswith("d"):
                os.system(self.exeConfPath+"/CRX2RNX " +os.path.join(obs_dir,file)+" -s")
                # effacer observation .d avec décompression .o
                os.remove(os.path.join(obs_dir,file)) # in case u need the file without hanataka decompression
                      

    def calcul_rtklib(self, obs_dir, rep_rec):
           #os.chdir(obs_dir)
           file_sp3 = utils.get_files_by_ext(obs_dir,"sp3")[0]
           #un seul fichier sp3
           file_brdc = utils.get_files_by_ext(obs_dir,"n")[0]
           #un seul fichier brdc
           files_obs = utils.get_files_by_ext(obs_dir,"o")
           #les fichiers observations des stations
           file_rec = utils.get_files_by_ext(rep_rec,"o")[0]
           file_conf = utils.get_files_by_ext(self.exeConfPath,"conf")[0]

           for i in range(len(files_obs)):
               os.system(self.exeConfPath+"/rnx2rtkp " +os.path.join(rep_rec,file_rec)+" "+os.path.join(obs_dir,files_obs[i])+" "+os.path.join(obs_dir,file_brdc)+" "+os.path.join(obs_dir,file_sp3)+" -k "+os.path.join(self.exeConfPath,file_conf)+" -o "+os.path.join(obs_dir,os.path.splitext(os.path.basename(files_obs[i]))[0])+".pos")
               print(self.exeConfPath+"/rnx2rtkp " +os.path.join(rep_rec,file_rec)+" "+files_obs[i]+" "+file_brdc+" "+file_sp3+" -k "+os.path.join(self.exeConfPath,file_conf)+" -o "+os.path.splitext(os.path.basename(files_obs[i]))[0]+".pos")
   
    def whatToWriteInRepport(self,obs_dir,requestDir):
        
        with open(os.path.join(obs_dir,"rapport.txt"), "w") as f:
            print("success 1 ecriture fichier")
            
            
            f.write("------------------------------------------------------------------\n")
            f.write("ENSG \t\t\tCALCUL GNSS EN LIGNE \n \t\t\t\tRTKLIB 2.4.2 \t\t\n")
            f.write("------------------------------------------------------------------\n")
            f.write("\n\n")
            f.write("ORBITES\t\t\t:"+os.path.basename(utils.get_files_by_ext(obs_dir,"sp3")[0])+".Z\n")
            f.write("1/ ELEMENTS EN ENTREE \n")
            f.write("------------------------------------------------------------------\n")
            (Xrec,Yrec,Zrec, head) = self.rinex_info(os.path.join(requestDir,self.RnxFileList[0]))
            f.write(head.__str__())
#            lines="FICHIER RINEX :"+self.RnxFileList[0]+"\n"+"EN-TETE NOM STATION :"+head.MARKER_NAME+"\n"+\
#            "EN-TETE NUMERO    :"+head.MARKER_NUMBER+"\nEN-TETE RECEPTEUR   : "+head.REC_TYPE+"EN-TETE ANTENNE :"+\
#            head.ANT_TYPE+"\nEN-TETE POSITION  : " +Xrec+"\t"+Yrec+"\t"+Zrec+"\n"+"EN-TETE ANT H/E/N  : "+\
#            head.dH+"\t"+head.dE+"\n"+head.dN+"\n"+"NOMBRES D'EPOQUES"+len(head.epochs)+"\n"+"DATE DEBUT     : "+\
#            str(print(self.tStart.st_iso_epoch(5)))+"\nDATE FIN   : "+str(print(self.tEnd.st_iso_epoch(5)))
#            #antennaFile = utils.get
            # l'idee c'est trouver l'instance de nom de l'antenne et puis rendre la premiére occurence 
            # et puis la deuxième qui est la dernière de NORTH / EAST / UP 
            # f.write(lines)
            P, X_chap,QX_chap,sigma02,V,list_station = utils.pod_pos(obs_dir,10)
            antenne_text = utils.get_files_by_ext(utils.get_exeConf_path(self.projectPath),"atx")[0]
            file_antenne = open(antenne_text, 'r')
            lines = file_antenne.readlines()
            bloc = utils.find_antenna_info(lines,head.ANT_TYPE)
            listENH ,listFreq=utils.find_ENH_atx(bloc)
            file_antenne.close()
            f.write("ANTENNE CENTRES DE PHASES N/E/H :\n")            
            for i in range(len(listENH)):
                strnhe =" ".join(listENH[i])
                a = head.ANT_TYPE +"\t" +listFreq[i]+"\t"+strnhe+"\n" #str1 = ''.join(list1)
                f.write(a)
            f.write("2/ STATIONS DE REFERENCE DANS UN RAYON DE "+str(self.max_distance*1000)+" m (MAX : "+str(self.station_number)+") \n")
            f.write("------------------------------------------------------------------\n")
            j =1
            P , X_chap,QX_chap,sigma02,V,list_station =utils.pod_pos(obs_dir,1)
            for stat in ((self.proche_stations_list)):
                line1 = str(j)+" "+ stat.nom +" : \t"+str(stat.last_dist)+"m\n"
                f.write(line1)
                if (stat.nom in self.proche_stations_names ) and any(stat.nom.lower() in s for s in list_station ) :
                    line2= stat.nom.lower()+str(self.tStart.doy)+"0."+str(self.tStart.yy)+"d.Z => "+\
                    stat.nom.lower()+str(self.tStart.doy)+"0."+str(self.tStart.yy)+"o\n"
                    f.write(line2)
                else:
                    f.write("ambiguités non résolues \n")
                j+=1
            
            f.write("3/ TRAITEMENT \n")
            f.write("------------------------------------------------------------------\n")
            S_xx ,S_yy,S_zz =utils.generateStd(QX_chap)
            E,N ,H, varENU , lon , lat, he =utils.gettingCoordinate(X_chap,QX_chap)
            f.write("TRAITEMENT FINALE AMBIGUITÉ RÉSOLUES FIXÉES \n")
            f.write("FACTEUR DE VARIANCE : "+str(sigma02[0][0]**(0.5))+"\n")
            f.write("PRECISION INTERNE : \n")
            f.write(" SX : "+str(S_xx)+" SY : "+str(S_yy)+" SZ : "+str(S_zz )+"\n")
            f.write(" SN : "+str((np.abs(varENU[1][1]))**0.5)+" SE : "+str((np.abs(varENU[0][0]))**0.5)+" SH : "+str((np.abs(varENU[1][1]))**0.5)+"\n")
            f.write("3/ RESULTATS \n") 
            f.write("=========================== RGF93 ===================================\n")
            f.write("POSITION RGF93 \n")
            f.write(" X:\t"+str(X_chap[0][0])+"\tY:\t"+str(X_chap[1][0])+"\tZ:\t"+str(X_chap[2][0])+"\n")
            f.write("COORDONNÉES GÉOGRAPHIQUES : \n")            
            f.write("LONGITUDE\t"+str(lon)+"°\tLATITUDE\t"+str(lat)+"°\tHELL\t"+str(he)+"\n")
            f.write("\t\t E "+utils.convertDDToDMS(lon)+"\t\t N "+utils.convertDDToDMS(lat)+"\t\t "+str(he)+"\n")
            f.write("LAMBERT-93 : E = "+str(E)+"m\t N = "+str(N)+"m"+" H "+str(H)+"\n") 
                
        f.close()
        return f
    
    
        
    
    
    def process(self,requestDir):
        """
        Fonction qui se déclenche pour le commencement du process de calcul
        """
        print('Starting rtklib automatic process we are here')
    
        t1 = gps.gpstime()
        
        requestFile = requestDir+'/request.xml'

        #R.directory = '/media/farah/Data/PPMD-PERSO/INFO_CODE/DEPOT_CALCUL/2016-11-04T12:12:56Z_172.31.42.114'  # pour récuperer le numéro de stations demandé
        #Create directory with the same name of the subdirctory which contains the request file
        obs_dir = os.path.join(self.observationPath,os.path.basename(requestDir))
        self.read(requestFile)
        self.rinex_info(os.path.join(requestDir,self.RnxFileList[0]))
        
        print("chemin",obs_dir)

        if not os.path.exists(obs_dir):
            os.makedirs(obs_dir)
        
        for stat in self.proche_stations_names:
            ftp=utils.connexionftp(self.exeConfPath)
            ficftp ="pub/data/"+str(self.tStart.yyyy)+"/"+str(self.tStart.doy)+"/data_30/"+stat.lower()+str(self.tStart.doy)+str("0.")+str(self.tStart.yy)+"d.Z"
            self.downloadftp(ftp,ficftp,obs_dir)
            
            utils.fermerftp(ftp)
           

        #téléchargement des orbites précise
        wk= self.tStart.wk
        wd = self.tStart.wd
       
        ficftp_orb_pr = "pub/products/ephemerides/"+str(wk)+"/igs"+str(wk)+str(int(wd))+".sp3.Z"
        
        ftp=utils.connexionftp(self.exeConfPath)

        self.download_or_precise(ftp,ficftp_orb_pr, obs_dir)

        ftp.quit()

        #téléchargement des éphémérides radio diffusés
        ficftp_radio ="pub/data/"+str(self.tStart.yyyy)+"/"+str(self.tStart.doy)+"/data_30/"+"brdc"+str(self.tStart.doy)+str("0.")+str(self.tStart.yy)+"n.Z"
        # ftp://rgpdata.ign.fr/pub/data/2016/153/data_30/brdc1530.16n.Z   --- n : gps g : glonass
        ftp=utils.connexionftp(self.exeConfPath)
        self.download_radio(ftp,ficftp_radio,obs_dir)

        self.unzip(obs_dir)     
         # décompression hanataka 
        self.gzip_crx(obs_dir)    
        #self.calcul_rtklib(obs_dir)
        self.calcul_rtklib(obs_dir,requestDir)

        #after extracting n pos file for the position our receiver we are going to send mail         
        self.whatToWriteInRepport(obs_dir,requestDir)
        rapports = utils.get_files_by_ext(obs_dir,"txt")        
        
        utils.send_mail(self.mail, "Position final", "Veuillez trouvez ci-joint le rapport de calcul GNSS",rapports,self.exeConfPath) 

        t2 = gps.gpstime()
        print ('%.3f sec elapsed ' % (t2-t1))
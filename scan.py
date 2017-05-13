# -*- coding: utf-8 -*-
"""
Created on %(date)s

@author: %(username)s
"""

import os
import pymysql
import shutil

#import smtplib
#from email.MIMEMultipart import MIMEMultipart
#from email.MIMEText import MIMEText
    

def instruction(avancement,cur,id_chantier,adresse_images,conn):
    #Récupération de l'instruction MicMac
    cur.execute("SELECT instruction FROM instructions WHERE id_chantier = (%s) AND ordre = (%s)", (id_chantier, ordre))
    instruction = cur.fetchone()
    
    #Lancement de l'instruction MicMac
    full_instruction = '/opt/micmac/culture3d/bin/mm3d '+instruction["instruction"]
    status = os.system(full_instruction)
    
    #TODO : gestion des différents plantages/avancement..
    
    #Quand c'est fini, incrémentation de l'avancement (rang de la commande qui vient d'être exécutée)
    cur.execute("UPDATE chantiers SET avancement = (%s) WHERE id_chantier = (%s)", (ordre,id_chantier))
    conn.commit()
    

#Connexion à la BDD

conn = pymysql.connect(host="localhost", user="root", passwd="root", db="micmac",charset='utf8mb4',cursorclass=pymysql.cursors.DictCursor)
cur = conn.cursor()

#Récupération d'un chantier en attente (celui avec le + petit ID = le + ancien)
cur.execute("SELECT * FROM chantiers WHERE statut = 'en_attente' ORDER BY id_chantier LIMIT 1")
chantier = cur.fetchone()

#S'il y a un chantier en attente, on le prend en charge
if chantier != None:

    #Récupération des données
    id_chantier = chantier["id_chantier"]
    nom_complet = chantier["nom_complet"]
    avancement = chantier["avancement"]
    adresse_images = chantier["adresse_dossier"]
    nb_instr = chantier["nb_etapes"]
    id_user = chantier["id_user"]
    
    #Passage de son statut à "en cours"
    cur.execute("UPDATE chantiers SET statut = 'en_cours' WHERE id_chantier = (%s)", (id_chantier,))
    
    #Accès au dossier contenant les images
    os.chdir(adresse_images)
    
    #Lancement de calcul.py autant de fois qu'il y a d'instructions
    for i in range(nb_instr):
        ordre = avancement + 1
        instruction(avancement,cur,id_chantier,adresse_images,conn)
        avancement += 1
    
    #Cas où tout s'est bien passé
    if avancement == 8:
        
        #Passage du statut à "termine"
        cur.execute("UPDATE chantiers SET statut = 'termine' WHERE id_chantier = (%s) ", (id_chantier,))
        
        #Préparation du fichier .zip
        os.chdir("..")
        shutil.make_archive(nom_complet, 'zip', nom_complet)
        print("zip ok !")
        
        #Récupération de l'adresse mail de l'utilisateur
        cur.execute("SELECT email FROM users WHERE id_user = (%s)", (id_user,))
        email_user = cur.fetchone()
        
        #Envoi du mail
#        email_mm4d = "mm4drone@gmail.com"
#        msg = MIMEMultipart()
#        msg['From'] = email_mm4d
#        msg['To'] = email_user
#        msg['Subject'] = "Votre chantier mm4d"
#         
#        body = "Votre chantier est terminé."
#        msg.attach(MIMEText(body, 'plain'))
#         
#        server = smtplib.SMTP('smtp.gmail.com', 587)
#        server.starttls()
#        server.login(email_mm4d, "mm4d_online")
#        text = msg.as_string()
#        server.sendmail(email_mm4d, email_user, text)
#        server.quit()
#        print("Email envoyé !")
        
else:
    print("Aucun chantier")
    
#Commit BDD
conn.commit()

#Fermeture de la connexion
cur.close()
conn.close()
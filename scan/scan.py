#!/usr/bin/env python
# -*- coding: utf-8 -*-

"""
Created on %(date)s

@author: %(username)s
"""

import os
import pymysql
import shutil

def instruction(avancement,cur,id_chantier,adresse_images,conn):
    #Récupération de l'instruction MicMac
    cur.execute("SELECT instruction FROM instructions WHERE id_chantier = (%s) AND ordre = (%s)", (id_chantier, ordre))
    instruction = cur.fetchone()
    
    #Lancement de l'instruction MicMac
    full_instruction = '/opt/micmac/bin/mm3d '+instruction["instruction"]
    status = os.system(full_instruction)
    
    #TODO : gestion des différents plantages/avancement..
    
    #Quand c'est fini, incrémentation de l'avancement (rang de la commande qui vient d'être exécutée)
    cur.execute("UPDATE chantiers SET avancement = (%s) WHERE id_chantier = (%s)", (ordre,id_chantier))
    conn.commit()
    
def nettoyage(garder_calcul, nom_complet):
    if garder_calcul == 1:
        #Déplacement dans le dossier MicMac
        os.chdir(nom_complet)
        
        #Suppression des 5 dossiers inutiles
        shutil.rmtree("Pastis")
        shutil.rmtree("Pyram")
        shutil.rmtree("Tmp-MM-Dir")
        shutil.rmtree("PIMs-TmpMnt")
        shutil.rmtree("PIMs-TmpMntOrtho")
        
        #Préparation du fichier .zip
        os.chdir("..")
        shutil.make_archive(nom_complet, 'zip', nom_complet)
        
        #Suppression du dossier original (on a la copie en .zip)
        shutil.rmtree(nom_complet)
        
    else:
        
        #Préparation du fichier .zip
        shutil.make_archive(nom_complet, 'zip', nom_complet+"_out")
        
        #Suppression du dossier original (on a la copie en .zip)
        shutil.rmtree(nom_complet)

if __name__ == "__main__":  
    
    #On se dirige dans le dossier contenant les images
    os.chdir("..")
    os.chdir("..")
    os.chdir("/var/www/html")
    
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
        nom_chantier = chantier["nom_chantier"]
        nom_complet = chantier["nom_complet"]
        avancement = chantier["avancement"]
        adresse_images = chantier["adresse_dossier"]
        nb_instr = chantier["nb_etapes"]
        id_user = chantier["id_user"]
        garder_calcul = chantier["garder_calcul"]
        
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
            
            #Passage du statut à "archivage"
            cur.execute("UPDATE chantiers SET statut = 'archivage' WHERE id_chantier = (%s) ", (id_chantier,))
            
            #Nettoyage du dossier et préparation du fichier zip
            os.chdir("..")
            
            #Création du dossier sortie
            os.mkdir(nom_complet+"_out")
            
            #Extraction des fichiers à conserver
            shutil.move(nom_complet+"/Nuage3D.ply", nom_complet+"_out/Nuage3D.ply")
            shutil.move(nom_complet+"/PIMs-ORTHO/Orthophotomosaic.tif", nom_complet+"_out/Orthophotomosaic.tif")
            shutil.move(nom_complet+"/PIMs-ORTHO/Orthophotomosaic.tfw", nom_complet+"_out/Orthophotomosaic.tfw")
            shutil.move(nom_complet+"/Ori-Rel/Residus.xml", nom_complet+"_out/Residus_TiePoints.xml")
            shutil.move(nom_complet+"/Ori-Bascule_L93/Result-Center-Bascule.xml", nom_complet+"_out/Residus_Terrain.xml")
        
            nettoyage(garder_calcul, nom_complet)
            
            #Passage du statut à "termine"
            cur.execute("UPDATE chantiers SET statut = 'termine' WHERE id_chantier = (%s) ", (id_chantier,))
            
            #Récupération de l'adresse mail de l'utilisateur
            cur.execute("SELECT email FROM users WHERE id_user = (%s)", (id_user,))
            email_user = cur.fetchone()
                   
    #Commit BDD
    conn.commit()
    
    #Fermeture de la connexion
    cur.close()
    conn.close()
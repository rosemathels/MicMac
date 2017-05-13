# -*- coding: utf-8 -*-

"""
Script de calcul servant à l'exécution d'une commande MicMac

Safa Fennia - Mouna Harrach - Rose Mathelier
MicMac en ligne - Projet développement ING2 2017 - ENSG
"""

import os
import pymysql
#
##Connexion à la BDD
#
#conn = pymysql.connect(host="localhost", user="root", passwd="root", db="micmac",charset='utf8mb4',cursorclass=pymysql.cursors.DictCursor)
#cur = conn.cursor()
#
##TODO : faire en sorte de scanner le dossier et/ou la BDD (équivalent du set_timeout ?) pour savoir s'il y a un nouveau job à faire
#
##Récupération d'un chantier en attente (celui avec le + petit ID = le + ancien)
#cur.execute("SELECT * FROM chantiers WHERE statut = 'en_attente' ORDER BY id_chantier LIMIT 1")
#chantier = cur.fetchone()
#
##Passage de son statut à "en cours"
#cur.execute("UPDATE chantiers SET statut = 'en_cours'")
#
##Récupération des données
#id_chantier = chantier["id_chantier"]
#avancement = chantier["avancement"]
#adresse_images = chantier["adresse_dossier"]

def instruction(avancement,cur,id_chantier,adresse_images,conn):
    #Récupération de l'instruction MicMac
    ordre = avancement + 1
    cur.execute("SELECT instruction FROM instructions WHERE id_chantier = (%s) AND ordre = (%s)", (id_chantier, ordre))
    instruction = cur.fetchone()
    
    #Accès au dossier contenant les images
    #ATTENTION AUX AUTORISATIONS !
    os.chdir(adresse_images)
    
    #Lancement de l'instruction MicMac
    full_instruction = '/opt/micmac/culture3d/bin/mm3d '+instruction["instruction"]
    print(full_instruction)
    status = os.system(full_instruction)
    
    #TODO : gestion des différents plantages/avancement..
    
    #Quand c'est fini, incrémentation de l'avancement (rang de la commande qui vient d'être exécutée)
    cur.execute("UPDATE chantiers SET avancement = (%s) WHERE id_chantier = (%s)", (ordre,id_chantier))
    conn.commit()
    
##Passage du statut à "en attente"
#if avancement == 8:
#    cur.execute("UPDATE chantiers SET statut = 'termine' WHERE id_chantier = (%s) ", (id_chantier,))
#else:
#    cur.execute("UPDATE chantiers SET statut = 'en_attente' WHERE id_chantier = (%s) ", (id_chantier,))
#
##Commit BDD
#conn.commit()
#
##Fermeture de la connexion
#cur.close()
#conn.close()

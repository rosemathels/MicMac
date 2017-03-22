# -*- coding: utf-8 -*-

"""
Script de calcul servant à l'exécution d'une commande MicMac

Safa Fennia - Mouna Harrach - Rose Mathelier
MicMac en ligne - Projet développement 2017 - ENSG
"""

import os
import psycopg2

#Connexion à la BDD
conn = psycopg2.connect("dbname=micmac user=postgres")
cur = conn.cursor()

#TODO : faire en sorte de scanner le dossier et/ou la BDD (équivalent du set_timeout ?) pour savoir s'il y a un nouveau job à faire

#Récupération d'un chantier en attente
cur.execute("SELECT * FROM chantier WHERE statut = 'en_attente'")
chantier = cur.fetchone()

#Passage de son statut à "en cours"
cur.execute("UPDATE chantier SET statut = 'en_cours'")

#Récupération des données
id_chantier = chantier["id"]
avancement = chantier["avancement"]
adresse_images = chantier["adresse"]

#Récupération de l'instruction MicMac
ordre = avancement + 1
instruction = cur.execute("SELECT instruction FROM instructions_micmac WHERE id_chantier = (%s) AND ordre = (%s)", (id_chantier, ordre))

#Accès au dossier contenant les images
os.system("cd "+adresse_images)

#Lancement de l'instruction MicMac
os.system(instruction)

#TODO : savoir quand l'instruction est terminée ?
#TODO : gestion des différents plantages

#Quand c'est fini, incrémentation de l'avancement (rang de la commande qui vient d'être exécutée)
cur.execute("UPDATE chantier SET avancement = (%s)", (ordre))

#Passage du statut à "en attente"
cur.execute("UPDATE chantier SET statut = 'en_attente'")

#Commit BDD
conn.commit()

#Fermeture de la connexion
cur.close()
conn.close()
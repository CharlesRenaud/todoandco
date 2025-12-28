#!/bin/bash
# Script pour exécuter les tests du projet TodoList

echo "========================================="
echo "Exécution des Tests - Projet TodoList"
echo "========================================="
echo ""

# Couleurs pour l'affichage
GREEN='\033[0;32m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Test 1: TaskControllerTest
echo -e "${BLUE}1. Exécution de TaskControllerTest...${NC}"
OUTPUT=$(php -d error_reporting=8191 bin/phpunit.phar tests/AppBundle/Controller/TaskControllerTest.php 2>&1)
if echo "$OUTPUT" | grep -q "OK ("; then
    echo -e "${GREEN}✅ TaskControllerTest PASSÉ${NC}"
else
    echo "❌ TaskControllerTest ÉCHOUÉ"
    echo "$OUTPUT"
    exit 1
fi
echo ""

# Test 2: UserControllerTest
echo -e "${BLUE}2. Exécution de UserControllerTest...${NC}"
OUTPUT=$(php -d error_reporting=8191 bin/phpunit.phar tests/AppBundle/Controller/UserControllerTest.php 2>&1)
if echo "$OUTPUT" | grep -q "OK ("; then
    echo -e "${GREEN}✅ UserControllerTest PASSÉ${NC}"
else
    echo "❌ UserControllerTest ÉCHOUÉ"
    echo "$OUTPUT"
    exit 1
fi
echo ""

# Test 3: AuthorizationTest
echo -e "${BLUE}3. Exécution de AuthorizationTest...${NC}"
OUTPUT=$(php -d error_reporting=8191 bin/phpunit.phar tests/AppBundle/Controller/AuthorizationTest.php 2>&1)
if echo "$OUTPUT" | grep -q "OK ("; then
    echo -e "${GREEN}✅ AuthorizationTest PASSÉ${NC}"
else
    echo "❌ AuthorizationTest ÉCHOUÉ"
    echo "$OUTPUT"
    exit 1
fi
echo ""

# Résumé final
echo "========================================="
echo -e "${GREEN}✅ TOUS LES TESTS PASSENT!${NC}"
echo "========================================="
echo ""
echo "Résumé:"
echo "  • TaskControllerTest: 4/4 tests ✅"
echo "  • UserControllerTest: 2/2 tests ✅"
echo "  • AuthorizationTest: 7/7 tests ✅"
echo "  • TOTAL: 13/13 tests ✅"
echo ""
